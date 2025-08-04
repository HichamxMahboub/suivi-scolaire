<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Eleve;

class ElevePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'encadrant', 'medical', 'teacher']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Eleve $eleve): bool
    {
        return $user->hasAnyRole(['admin', 'encadrant', 'medical', 'teacher']);
    }

    /**
     * Determine whether the user can view medical information.
     */
    public function viewMedical(User $user, Eleve $eleve): bool
    {
        return $user->canViewMedicalInfo();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'encadrant']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Eleve $eleve): bool
    {
        return $user->hasAnyRole(['admin', 'encadrant']);
    }

    /**
     * Determine whether the user can update medical information.
     */
    public function updateMedical(User $user, Eleve $eleve): bool
    {
        return $user->canEditMedicalInfo();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Eleve $eleve): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Eleve $eleve): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Eleve $eleve): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can manage notes.
     */
    public function manageNotes(User $user, Eleve $eleve): bool
    {
        return $user->canManageNotes();
    }
}
