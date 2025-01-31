<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'application' => 'required|string|max:255',
            'message' => 'required|string|max:255'
        ]);

        $ideasToday = $request->user()->ideas()
            ->whereDate('created_at', now()->toDateString())
            ->count();


        if ($ideasToday >= 2) {
            return redirect()->route('ideas.index')->with('error', 'Vous avez atteint la limite de deux idées par jour.');
        }

        $request->user()->ideas()->create($validated);
        return redirect()->route('ideas.index');
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
        // retourner la view
        $this->authorize('update', $idea);
        return view('ideas.edit', [
            'idea' => $idea,
        ]);
    }


    public function comments(Request $request)
    {

        $validated = $request->validate([
            'comment' => 'required|string|max:255',
            'idea_id' => 'required|int'
        ]);
        $request->user()->comments()->create($validated);
        return redirect()->route('ideas.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Idea $idea)
    {
        // Contrôler les données
        $this->authorize('update', $idea);
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
        // Update
        $idea->update($validated);
        //Rediriger
        return redirect(route('ideas.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        // contrôle autorisation pour delete
        $this->authorize('delete', $idea);
        // delete
        $idea->delete();
        // Rediriger
        return redirect(route('ideas.index'));
    }
}
