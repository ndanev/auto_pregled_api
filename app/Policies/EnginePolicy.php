<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Engine;

class EnginePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Engine $engine): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Engine $engine): bool
    {
        return true;
    }

    public function delete(Admin $admin, Engine $engine): bool
    {
        return true;
    }
}
