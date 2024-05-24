<?php

namespace App\Contracts;

interface UserRepository
{
    public function create();

    public function update();

    public function delete();

    public function find(string $column, string $value);

    public function findAll();
}
