<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
   {
    return view('ideas.index', [

        'comments' => Comment::with('user')->latest()->get(),

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
            'idea_id' => 'required|int',
            'user_id' => 'required|int',
            'comment' => 'required|string|max:255',
        ]);

        $commentCount = Comment::where('idea_id', $validated['idea_id'])
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($commentCount >= 3) {
            return redirect()->route('ideas.index')->with('max_idea' ,'Cette idée a déjà atteint le maximum de 3 commentaires pour aujourd\'hui.');
        }
        $request->user()->comments()->create($validated);
    
        return redirect()->route('ideas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
       /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        // retourner la view
        $this->authorize('update', $comment);
        return view('comments.edit', [
            'comment' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Contrôler les données
        $this->authorize('update', $comment);
        $validated = $request->validate([
            'comment' => 'required|string|max:255',
        ]);
        // Update
        $comment->update($validated);
        //Rediriger
        return redirect(route('ideas.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
        /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // contrôle autorisation pour delete
        $this->authorize('delete', $comment);
        // delete
        $comment->delete();
        // Rediriger
        return redirect(route('ideas.index'));
    }

}
