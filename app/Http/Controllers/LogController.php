<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // Nettoyer tout output buffer existant
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        $query = Log::query();

        // Filtres de date
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filtre par niveau
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Ajout de l'ordre décroissant sur created_at
        $query->orderBy('created_at', 'desc');

        $logs = $query->paginate(15)->withQueryString();

        return view('logs.index', [
            'logs' => $logs,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'level' => $request->level
        ]);
    }
} 