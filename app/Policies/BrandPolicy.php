<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Brand;

class BrandPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Brand $brand): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Brand $brand): bool
    {
        return true;
    }

    public function delete(Admin $admin, Brand $brand): bool
    {
        return true;
    }
}
