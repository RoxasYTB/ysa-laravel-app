<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la table logs existe
        if (!Schema::hasTable('logs')) {
            Schema::create('logs', function ($table) {
                $table->id();
                $table->string('type', 50);
                $table->string('level', 20);
                $table->text('message');
                $table->json('context')->nullable();
                $table->timestamps();
            });
        }

        // Supprimer les tables dans l'ordre correct des dépendances
        Schema::dropIfExists('comments');
        Schema::dropIfExists('ideas');

        // Recréer la table ideas
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('application')->nullable();
            $table->text('message')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Recréer la table comments
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idea_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->timestamps();
            
            $table->foreign('idea_id')->references('id')->on('ideas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Nettoyer la sortie du buffer
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Suppression des triggers existants par précaution
        DB::unprepared('DROP TRIGGER IF EXISTS after_idea_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_idea_update');
        DB::unprepared('DROP TRIGGER IF EXISTS before_idea_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS after_comment_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_comment_update');
        DB::unprepared('DROP TRIGGER IF EXISTS before_comment_delete');

        // Trigger pour la création d'une idée
        DB::unprepared('
            CREATE TRIGGER after_idea_insert
            AFTER INSERT ON ideas
            FOR EACH ROW
            BEGIN
                INSERT INTO logs (type, level, message, context, created_at, updated_at)
                VALUES (
                    "idea",
                    "info",
                    CONCAT("Nouvelle idée créée : ", NEW.title),
                    JSON_OBJECT(
                        "idea_id", NEW.id,
                        "title", NEW.title,
                        "created_by", NEW.user_id
                    ),
                    NOW(),
                    NOW()
                );
            END;
        ');

        // Trigger pour la modification d'une idée
        DB::unprepared('
            CREATE TRIGGER after_idea_update
            AFTER UPDATE ON ideas
            FOR EACH ROW
            BEGIN
                DECLARE changes JSON;
                SET changes = JSON_OBJECT();
                
                IF NOT(OLD.title <=> NEW.title) THEN
                    SET changes = JSON_SET(changes, "$.title", JSON_OBJECT(
                        "old", OLD.title,
                        "new", NEW.title
                    ));
                END IF;

                IF NOT(OLD.application <=> NEW.application) THEN
                    SET changes = JSON_SET(changes, "$.application", JSON_OBJECT(
                        "old", OLD.application,
                        "new", NEW.application
                    ));
                END IF;
                
                IF NOT(OLD.message <=> NEW.message) THEN
                    SET changes = JSON_SET(changes, "$.message", JSON_OBJECT(
                        "old", OLD.message,
                        "new", NEW.message
                    ));
                END IF;
                
                IF JSON_LENGTH(changes) > 0 THEN
                    INSERT INTO logs (type, level, message, context, created_at, updated_at)
                    VALUES (
                        "idea",
                        "warning",
                        CONCAT("Modification de l\'idée : ", NEW.title),
                        JSON_OBJECT(
                            "idea_id", NEW.id,
                            "changes", changes,
                            "modified_by", NEW.user_id
                        ),
                        NOW(),
                        NOW()
                    );
                END IF;
            END;
        ');

        // Trigger pour la suppression d'une idée
        DB::unprepared('
            CREATE TRIGGER before_idea_delete
            BEFORE DELETE ON ideas
            FOR EACH ROW
            BEGIN
                DECLARE deletion_reason VARCHAR(100);
                SET deletion_reason = IFNULL((SELECT @deletion_reason), "Suppression manuelle par l\'utilisateur");
                
                INSERT INTO logs (type, level, message, context, created_at, updated_at)
                VALUES (
                    "idea",
                    "alert",
                    CONCAT("Suppression de l\'idée : ", OLD.title),
                    JSON_OBJECT(
                        "idea_id", OLD.id,
                        "title", OLD.title,
                        "application", OLD.application,
                        "message", OLD.message,
                        "deleted_by", OLD.user_id,
                        "deletion_reason", deletion_reason
                    ),
                    NOW(),
                    NOW()
                );
            END;
        ');

        // Trigger pour la création d'un commentaire
        DB::unprepared('
            CREATE TRIGGER after_comment_insert
            AFTER INSERT ON comments
            FOR EACH ROW
            BEGIN
                INSERT INTO logs (type, level, message, context, created_at, updated_at)
                VALUES (
                    "comment",
                    "info",
                    CONCAT("Nouveau commentaire ajouté sur l\'idée #", NEW.idea_id),
                    JSON_OBJECT(
                        "comment_id", NEW.id,
                        "idea_id", NEW.idea_id,
                        "created_by", NEW.user_id,
                        "content", NEW.comment
                    ),
                    NOW(),
                    NOW()
                );
            END;
        ');

        // Trigger pour la modification d'un commentaire
        DB::unprepared('
            CREATE TRIGGER after_comment_update
            AFTER UPDATE ON comments
            FOR EACH ROW
            BEGIN
                IF NOT(OLD.comment <=> NEW.comment) THEN
                    INSERT INTO logs (type, level, message, context, created_at, updated_at)
                    VALUES (
                        "comment",
                        "warning",
                        CONCAT("Modification du commentaire #", NEW.id),
                        JSON_OBJECT(
                            "comment_id", NEW.id,
                            "idea_id", NEW.idea_id,
                            "changes", JSON_OBJECT(
                                "old_content", OLD.comment,
                                "new_content", NEW.comment
                            ),
                            "modified_by", NEW.user_id
                        ),
                        NOW(),
                        NOW()
                    );
                END IF;
            END;
        ');

        // Trigger pour la suppression d'un commentaire
        DB::unprepared('
            CREATE TRIGGER before_comment_delete
            BEFORE DELETE ON comments
            FOR EACH ROW
            BEGIN
                DECLARE deletion_reason VARCHAR(100);
                SET deletion_reason = IFNULL((SELECT @deletion_reason), "Suppression manuelle par l\'utilisateur");
                
                INSERT INTO logs (type, level, message, context, created_at, updated_at)
                VALUES (
                    "comment",
                    "alert",
                    CONCAT("Suppression du commentaire #", OLD.id, " sur l\'idée #", OLD.idea_id),
                    JSON_OBJECT(
                        "comment_id", OLD.id,
                        "idea_id", OLD.idea_id,
                        "content", OLD.comment,
                        "deleted_by", OLD.user_id,
                        "deletion_reason", deletion_reason
                    ),
                    NOW(),
                    NOW()
                );
            END;
        ');
    }

    public function down()
    {
        // Suppression des triggers dans l'ordre inverse
        DB::unprepared('DROP TRIGGER IF EXISTS before_comment_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS after_comment_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_comment_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS before_idea_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS after_idea_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_idea_insert');

        // Supprimer les tables dans l'ordre inverse des dépendances
        Schema::dropIfExists('comments');
        Schema::dropIfExists('ideas');
    }
};