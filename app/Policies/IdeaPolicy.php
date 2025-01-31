<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class IdeaPolicy
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
    // public function view(User $user, Idea $idea): bool
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
    public function edit(Idea $idea)
    {
        // retourner la view
        $this->authorize('update', $idea);
        return view('ideas.edit', [
            'idea' => $idea,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
        /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Idea $idea): bool
    {
        // le user associé au idea peut le modifier s'il est identique au user en paramètre
        return $idea->user()->is($user);
    }



    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Idea $idea): bool
    {
        // le user associé au chirp peut le supprimer s'il est identique au user en paramètre
        return $this->update($user, $idea);
    }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(User $user, Idea $idea): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, Idea $idea): bool
    // {
    //     //
    // }
}
