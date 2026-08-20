<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\CarModel;

class CarModelPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, CarModel $model): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, CarModel $model): bool
    {
        return true;
    }

    public function delete(Admin $admin, CarModel $model): bool
    {
        return true;
    }
}
