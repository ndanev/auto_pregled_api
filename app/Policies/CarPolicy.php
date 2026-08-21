<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Car;

class CarPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Car $car): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Car $car): bool
    {
        return true;
    }

    public function delete(Admin $admin, Car $car): bool
    {
        return true;
    }
}
