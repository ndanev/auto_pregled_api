<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Image;

class ImagePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Image $image): bool
    {
        return true;
    }

    public function delete(Admin $admin, Image $image): bool
    {
        return true;
    }
}
