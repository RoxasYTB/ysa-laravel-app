<?php

namespace App\Services;

use App\Models\Idea;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class DatabaseCleanerService
{
    /**
     * Delete ideas and comments containing HTML, XML, XSS
     *
     * @return array Statistics about deleted items
     */
    public function cleanDatabase()
    {
        $stats = [
            'ideas_deleted' => 0,
            'comments_deleted' => 0,
        ];

        try {
            DB::beginTransaction();
            
            // Process ideas - delete suspicious ones
            $ideas = Idea::all();
            foreach ($ideas as $idea) {
                $deletionReason = $this->detectDeletionReason($idea->title, $idea->application, $idea->message);
                
                if ($deletionReason) {
                    // Log the suspicious idea
                    Log::warning('Detected suspicious idea', [
                        'id' => $idea->id,
                        'title' => $idea->title,
                        'application' => $idea->application,
                        'message_sample' => substr($idea->message, 0, 50) . '...',
                        'deletion_reason' => $deletionReason
                    ]);
                    
                    // Set the deletion reason before deleting
                    DB::statement('SET @deletion_reason = ?', [$deletionReason]);
                    
                    // Delete the idea directly
                    $idea->delete();
                    $stats['ideas_deleted']++;
                }
            }
            
            // Process comments - delete suspicious ones
            $comments = Comment::all();
            foreach ($comments as $comment) {
                $deletionReason = $this->detectDeletionReason($comment->comment);
                
                if ($deletionReason) {
                    // Log the suspicious comment
                    Log::warning('Detected suspicious comment', [
                        'id' => $comment->id,
                        'comment_sample' => substr($comment->comment, 0, 50) . '...',
                        'deletion_reason' => $deletionReason
                    ]);
                    
                    // Set the deletion reason before deleting
                    DB::statement('SET @deletion_reason = ?', [$deletionReason]);
                    
                    // Delete the comment
                    $comment->delete();
                    $stats['comments_deleted']++;
                }
            }
            
            DB::commit();
            return $stats;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Database cleaning failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Detect the reason for deletion based on content analysis
     */
    private function detectDeletionReason($content, $title = null, $message = null)
    {
        // If multiple content pieces are provided, check each
        $contents = array_filter([$content, $title, $message]);
        
        foreach ($contents as $text) {
            // Skip empty content
            if (empty($text)) {
                continue;
            }
            
            // Check for HTML tags
            if ($text != strip_tags($text)) {
                return "Contenu HTML/XML détecté";
            }
            
            // Check for common XSS patterns
            $jsPatterns = ['/javascript:/i', '/eval\(/i', '/\<script/i', '/alert\(/i', '/document\./i', '/window\./i'];
            foreach ($jsPatterns as $pattern) {
                if (preg_match($pattern, $text)) {
                    return "JavaScript potentiellement malveillant détecté";
                }
            }
            
            // Check for event handlers
            $eventPatterns = ['/on\w+=/i', '/onerror/i', '/onclick/i', '/onload/i', '/onmouseover/i'];
            foreach ($eventPatterns as $pattern) {
                if (preg_match($pattern, $text)) {
                    return "Gestionnaire d'événements JavaScript détecté";
                }
            }
            
            // Check for other suspicious patterns
            $otherPatterns = [
                '/\<iframe/i' => "Balise iframe détectée",
                '/\<img/i' => "Balise image potentiellement malveillante",
                '/&#/i' => "Encodage HTML suspect détecté",
                '/\\\u/i' => "Encodage Unicode suspect détecté",
                '/\.cookie/i' => "Manipulation de cookies détectée",
                '/\.location/i' => "Manipulation de l'URL détectée",
                '/localStorage/i' => "Accès au stockage local détecté",
                '/sessionStorage/i' => "Accès au stockage de session détecté"
            ];
            
            foreach ($otherPatterns as $pattern => $reason) {
                if (preg_match($pattern, $text)) {
                    return $reason;
                }
            }
        }
        
        // If we reach here and the content is different from the stripped content
        if ($content != strip_tags($content) || 
            ($title && $title != strip_tags($title)) || 
            ($message && $message != strip_tags($message))) {
            return "Contenu HTML/XML détecté";
        }
        
        return false;
    }
    
    /**
     * Check if content contains HTML or potential XSS
     */
    private function containsHtmlOrXss($content)
    {
        return (bool) $this->detectDeletionReason($content);
    }
}
