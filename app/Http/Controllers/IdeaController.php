<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ideas.index', [
            'ideas' => Idea::with('user')->latest()->get(),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'application' => 'required|string|max:255',
                'message' => 'required|string|max:255'
            ]);

            $ideasToday = $request->user()->ideas()
                ->whereDate('created_at', now()->toDateString())
                ->count();

            if ($ideasToday >= 2) {
                return redirect()->route('ideas.index')
                    ->with('error', 'Vous avez atteint la limite de deux idées par jour.');
            }

            $request->user()->ideas()->create($validated);
            return redirect()->route('ideas.index')
                ->with('success', 'Votre idée a été créée avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'idée: ' . $e->getMessage(), [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('ideas.index')
                ->with('error', 'Une erreur est survenue lors de la création de l\'idée. Détails: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        try {
            $this->authorize('update', $idea);
            return view('ideas.edit', [
                'idea' => $idea,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'édition de l\'idée: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'idea_id' => $idea->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('ideas.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette idée. Détails: ' . $e->getMessage());
        }
    }


    public function comments(Request $request)
    {
        try {
            $validated = $request->validate([
                'comment' => 'required|string|max:255',
                'idea_id' => 'required|int'
            ]);
            $request->user()->comments()->create($validated);
            return redirect()->route('ideas.index')
                ->with('success', 'Votre commentaire a été ajouté.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du commentaire: ' . $e->getMessage(), [
                'user_id' => $request->user()->id,
                'idea_id' => $request->idea_id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('ideas.index')
                ->with('error', 'Une erreur est survenue lors de l\'ajout du commentaire. Détails: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Idea $idea)
    {
        try {
            // Autorisation
            $this->authorize('update', $idea);

            // Validation
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'application' => 'required|string|max:255',
                'message' => 'required|string|max:255'
            ]);

            // Préparer les changements
            $changes = [];
            foreach ($validated as $field => $value) {
                if ($idea->$field !== $value) {
                    $changes[$field] = [
                        'old' => $idea->$field,
                        'new' => $value
                    ];
                }
            }

            // Mise à jour
            $idea->update($validated);

            // Log uniquement si des changements ont été effectués
            if (!empty($changes)) {
                Log::info("Modification de l'idée #{$idea->id}", [
                    'idea_id' => $idea->id,
                    'changes' => json_encode($changes),
                    'modified_by' => auth()->id()
                ]);
            }

            return redirect()->route('ideas.index')
                ->with('success', "L'idée a été modifiée avec succès.");

        } catch (\Exception $e) {
            Log::error("Erreur lors de la modification de l'idée #{$idea->id}", [
                'idea_id' => $idea->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('ideas.index')
                ->with('error', "Une erreur est survenue lors de la modification de l'idée.");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Idea $idea)
    {
        try {
            // contrôle autorisation pour delete
            $this->authorize('delete', $idea);
            
            // Determine deletion reason - default to manual deletion if not specified
            $deletionReason = $request->input('deletion_reason', 'Suppression manuelle par l\'utilisateur');
            
            // Set deletion reason for the trigger
            \DB::statement('SET @deletion_reason = ?', [$deletionReason]);
            
            // Log the deletion with reason before it occurs
            Log::info('Suppression d\'une idée', [
                'idea_id' => $idea->id,
                'title' => $idea->title,
                'user_id' => auth()->id(),
                'deletion_reason' => $deletionReason
            ]);
            
            // Delete the idea
            $idea->delete();
            
            // If request is AJAX/API, return JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'L\'idée a été supprimée avec succès.',
                    'reason' => $deletionReason
                ]);
            }
            
            // Otherwise redirect with flash message
            return redirect()->route('ideas.index')
                ->with('success', 'L\'idée a été supprimée avec succès.')
                ->with('deletion_reason', $deletionReason); // Pass reason to flash message if needed
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'idée: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'idea_id' => $idea->id,
                'deletion_reason' => $request->input('deletion_reason'),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // If request is AJAX/API, return JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la suppression de l\'idée: ' . $e->getMessage()
                ], 500);
            }
            
            // Otherwise redirect with flash message
            return redirect()->route('ideas.index')
                ->with('error', 'Une erreur est survenue lors de la suppression de l\'idée. Détails: ' . $e->getMessage());
        }
    }
}
