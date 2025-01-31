<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CommentPolicy
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    // /**
    //  * Determine whether the user can view any models.
    //  */
    // public function viewAny(User $user): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can view the model.
    //  */
    // public function view(User $user, Comment $comment): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can create models.
    //  */
    // public function create(User $user): bool
    // {
    //     //
    // }

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
        /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        // le user associé au comment peut le modifier s'il est identique au user en paramètre
        return $comment->user()->is($user);
    }



    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // le user associé au chirp peut le supprimer s'il est identique au user en paramètre
        return $this->update($user, $comment);
    }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(User $user, Comment $comment): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, Comment $comment): bool
    // {
    //     //
    // }
}
