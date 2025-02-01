<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaterialPolicy
{
    /**
    * Determine whether the user can update the model.
    */
    public function update(User $user, Material $material): bool
    {
        return $user->id === $material->user_id;
    }

    /**
    * Determine whether the user can delete the model.
    */
    public function delete(User $user, Material $material): bool
    {
        return $user->id === $material->user_id;
    }
    
    
}