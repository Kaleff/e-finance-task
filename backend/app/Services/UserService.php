<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function getUsers(): Collection
    {
        return User::select('id', 'name', 'email')->get();
    }
}
