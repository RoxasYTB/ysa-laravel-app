<?php

namespace App\Http\Controllers;

use App\Services\DatabaseCleanerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DatabaseCleanerController extends Controller
{
    /**
     * Clean the database content by deleting suspicious content
     */
    public function clean(DatabaseCleanerService $cleaner)
    {
        try {
            // Log the start of cleaning
            Log::info('Starting database cleaning process - deleting suspicious content');
            
            $stats = $cleaner->cleanDatabase();
            
            // Log the results
            Log::info('Database cleaned successfully', ['stats' => $stats]);
            
            return response()->json([
                'success' => true,
                'message' => 'Suspicious content deleted successfully',
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            // Log the error
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
