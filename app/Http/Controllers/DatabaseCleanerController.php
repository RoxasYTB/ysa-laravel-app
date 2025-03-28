<?php

namespace App\Http\Controllers;

use App\Services\DatabaseCleanerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DatabaseCleanerController extends Controller
{
    public function clean(Request $request, DatabaseCleanerService $cleaner)
    {
        try {
            // Collect deletion reasons
            $reasons = [];
            
            // Set up a listener to capture deletion reasons
            \DB::listen(function($query) use (&$reasons) {
                if (strpos($query->sql, '@deletion_reason') !== false && !empty($query->bindings)) {
                    foreach ($query->bindings as $binding) {
                        if (is_string($binding) && !in_array($binding, $reasons)) {
                            $reasons[] = $binding;
                        }
                    }
                }
            });
            
            // Run the cleaner
            $stats = $cleaner->cleanDatabase();
            
            Log::info('Database content cleaning executed via web interface', [
                'stats' => $stats,
                'reasons' => $reasons,
                'user_id' => $request->user() ? $request->user()->id : 'guest'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Suspicious content has been removed from the database.',
                'stats' => $stats,
                'reasons' => array_unique($reasons)
            ]);
        } catch (\Exception $e) {
            Log::error('Database cleaning failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete suspicious content: ' . $e->getMessage()
            ], 500);
        }
    }
}
