<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Generation;

class GenerationPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Generation $generation): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Generation $generation): bool
    {
        return true;
    }

    public function delete(Admin $admin, Generation $generation): bool
    {
        return true;
    }
}
