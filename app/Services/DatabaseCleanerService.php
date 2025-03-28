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
                if ($this->containsHtmlOrXss($idea->title) || 
                    $this->containsHtmlOrXss($idea->application) || 
                    $this->containsHtmlOrXss($idea->message)) {
                    
                    // Log the suspicious idea
                    Log::warning('Detected suspicious idea', [
                        'id' => $idea->id,
                        'title' => $idea->title,
                        'application' => $idea->application,
                        'message_sample' => substr($idea->message, 0, 50) . '...'
                    ]);
                    
                    // Delete the idea via HTTP request
                    $this->deleteIdea($idea->id);
                    
                    $stats['ideas_deleted']++;
                }
            }
            
            // Process comments - delete suspicious ones
            $comments = Comment::all();
            foreach ($comments as $comment) {
                if ($this->containsHtmlOrXss($comment->comment)) {
                    // Log the suspicious comment
                    Log::warning('Detected suspicious comment', [
                        'id' => $comment->id,
                        'comment_sample' => substr($comment->comment, 0, 50) . '...'
                    ]);
                    
                    // Delete the comment
                    $comment->delete();
                    $stats['comments_deleted']++;
                }
            }
            
            DB::commit();
            Log::info('Database cleaning completed', ['stats' => $stats]);
            return $stats;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Database cleaning failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Delete an idea using the same approach as the HTTP request example
     */
    private function deleteIdea($id)
    {
        try {
            // First approach: Use the model to delete (cleaner and more secure)
            $idea = Idea::find($id);
            if ($idea) {
                $idea->delete();
                Log::info("Deleted idea $id via model");
                return true;
            }
            
            // If model delete doesn't work for some reason, try HTTP approach
            $baseUrl = config('app.url');
            $token = csrf_token();
            
            // Perform HTTP request mimicking a form submission
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'text/html,application/xhtml+xml,application/xml',
                'Referer' => "$baseUrl/ideas"
            ])->asForm()->post("$baseUrl/ideas/$id", [
                '_token' => $token,
                '_method' => 'delete'
            ]);
            
            if ($response->successful()) {
                Log::info("Deleted idea $id via HTTP request");
                return true;
            } else {
                Log::error("Failed to delete idea $id. Status: " . $response->status());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error deleting idea $id: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if content contains HTML or potential XSS
     */
    private function containsHtmlOrXss($content)
    {
        if (empty($content)) {
            return false;
        }
        
        // Check for HTML tags
        if ($content != strip_tags($content)) {
            return true;
        }
        
        // Check for common XSS patterns (extended list)
        $xssPatterns = [
            '/javascript:/i',
            '/on\w+=/i',
            '/\<script/i',
            '/\<\/script/i',
            '/\<iframe/i',
            '/\<\/iframe/i',
            '/eval\(/i',
            '/expression\(/i',
            '/vbscript:/i',
            '/alert\(/i',
            '/document\./i',
            '/window\./i',
            '/\.cookie/i',
            '/\.location/i',
            '/\<img/i',
            '/onerror/i',
            '/onclick/i',
            '/onload/i',
            '/onmouseover/i',
            '/&#/i',
            '/\\\u/i',
            '/fromCharCode/i',
            '/encodeURI/i',
            '/localStorage/i',
            '/sessionStorage/i',
        ];
        
        foreach ($xssPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }
}
