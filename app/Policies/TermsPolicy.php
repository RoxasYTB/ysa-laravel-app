<?php

namespace App\Policies;

use App\Models\Terms;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class TermsPolicy
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Terms $terms): bool
    {
        return true;
    }

}
