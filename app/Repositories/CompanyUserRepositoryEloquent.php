<?php

namespace App\Repositories;

use App\Contracts\CompanyUserRepository;
use App\Models\User;

class CompanyUserRepositoryEloquent implements CompanyUserRepository
{
    protected User $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

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
        return $this->findAll()->where($column, $value)->first();
    }

    public function findAll()
    {
        $query = User::query();

        // Managers can see sub-company users
        if ($this->user->hasRole('manager')) {
            $query->whereHas('company', function ($query) {
                $query->where('id', $this->user->company_id)
                    ->orWhere('parent_id', $this->user->company_id);
            });
        } else { // Limit results to their own company
            $query->whereHas('company', function ($query) {
                $query->where('id', $this->user->company_id);
            });
        }

        // Limit the type of user (roles) that they can see
        $query->where(function ($query) {
            $query->whereRaw('1 = 0');
            if ($this->user->hasPermissionTo('view users')) {
                $query->orWhereHas('roles', function ($query) {
                    $query->where('name', 'user');
                });
            }
            if ($this->user->hasPermissionTo('view supervisors')) {
                $query->orWhereHas('roles', function ($query) {
                    $query->where('name', 'supervisor');
                });
            }
            if ($this->user->hasPermissionTo('view managers')) {
                $query->orWhereHas('roles', function ($query) {
                    $query->where('name', 'manager');
                });
            }
        });

        return $query;
    }
}
