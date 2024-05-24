<?php

namespace App\Repositories;

use App\Contracts\CompanyRepository;

class CompanyRepositoryEloquent implements CompanyRepository
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
