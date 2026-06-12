<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Calculation;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalculationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any calculations.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view their own calculations
    }

    /**
     * Determine whether the user can view the calculation.
     */
    public function view(User $user, Calculation $calculation): bool
    {
        return $user->id === $calculation->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create calculations.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create calculations
    }

    /**
     * Determine whether the user can update the calculation.
     */
    public function update(User $user, Calculation $calculation): bool
    {
        return $user->id === $calculation->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the calculation.
     */
    public function delete(User $user, Calculation $calculation): bool
    {
        return $user->id === $calculation->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can share the calculation.
     */
    public function share(User $user, Calculation $calculation): bool
    {
        return $user->id === $calculation->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can clone the calculation.
     */
    public function clone(User $user, Calculation $calculation): bool
    {
        return $user->id === $calculation->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can print the calculation.
     */
    public function print(User $user, Calculation $calculation): bool
    {
        return $user->id === $calculation->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can generate tree for calculation.
     */
    public function generateTree(User $user, Calculation $calculation): bool
    {
        return $user->id === $calculation->user_id || $user->isAdmin();
    }
}