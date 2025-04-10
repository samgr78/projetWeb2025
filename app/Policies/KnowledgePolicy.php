<?php

namespace App\Policies;

use App\Models\Knowledge;
use App\Models\User;

class KnowledgePolicy
{
    /**
     * Create a new policy instance.
     */
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
    public function view(User $user, Knowledge $knowledge): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->school()->pivot->role == 'teacher';
    }

    public function createLanguage(User $user): bool{
        return $user->school()->pivot->role == 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Knowledge $knowledge): bool
    {
        return $user->school()->pivot->role == 'teacher';
    }

    public function check(User $user, Knowledge $knowledge): bool{
        return $user->school()->pivot->role == 'teacher';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Knowledge $knowledge): bool
    {
        return $user->school()->pivot->role == 'teacher';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Knowledge $knowledge): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Knowledge $knowledge): bool
    {
        return $user->school()?->pivot->role==='teacher';
    }
}
