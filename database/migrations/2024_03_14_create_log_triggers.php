<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        // Nettoyer la sortie du buffer
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Suppression des triggers existants par précaution
        DB::unprepared('DROP TRIGGER IF EXISTS after_idea_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_idea_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_comment_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_comment_update');

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
    }

    public function down()
    {
        // Suppression des triggers dans l'ordre inverse
        DB::unprepared('DROP TRIGGER IF EXISTS after_comment_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_comment_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_idea_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_idea_insert');
    }
}; 