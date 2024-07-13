<?php

namespace App\Policies;

use App\Models\Period;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PeriodPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('periods.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Period $period): bool
    {
        return $user->hasPermission('period.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('period.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Period $period): bool
    {
        return $user->hasPermission('period.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Period $period): bool
    {
        return $user->hasPermission('period.delete');
    }

}
