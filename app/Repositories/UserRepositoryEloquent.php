<?php

namespace App\Repositories;

use App\Contracts\UserRepository;

class UserRepositoryEloquent implements UserRepository
{
    public function create()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
    }

    public function find(string $column, string $value)
    {
        return null;
    }

    public function findAll()
    {
        return collect([]);
    }
}
