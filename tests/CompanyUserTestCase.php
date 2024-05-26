<?php

namespace Tests;

use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyUserTestCase extends TestCase
{
    use RefreshDatabase;

    protected $now;
    protected $companyParent;
    protected $companies;
    protected $usersParent;
    protected $users1;
    protected $users2;
    protected $supervisors;

    protected function setUp(): void
    {
        parent::setUp();

        $this->now = Carbon::now();

        $this->companyParent = Company::factory()->create();
        $this->companies = Company::factory()->state([
            'parent_id' => $this->companyParent->id,
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ])
            ->count(2)->create();

        $this->usersParent = User::factory()->state([
            'company_id' => $this->companyParent->id,
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ])
            ->count(2)->create();
        $this->usersParent[0]->assignRole('admin');
        $this->usersParent[1]->assignRole('manager');

        $this->users1 = User::factory()->state([
            'company_id' => $this->companies[0]->id,
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ])
            ->count(4)->create();
        $this->users1[0]->assignRole('admin');
        $this->users1[1]->assignRole('supervisor');
        foreach ($this->users1->slice(2) as $user) {
            $user->assignRole('user');
        }

        $this->users2 = User::factory()->state([
            'company_id' => $this->companies[1]->id,
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ])
            ->count(4)->create();
        $this->users2[0]->assignRole('admin');
        $this->users2[1]->assignRole('supervisor');
        foreach ($this->users2->slice(2) as $user) {
            $user->assignRole('user');
        }

        $this->supervisors = collect([$this->users1[1], $this->users2[1]]);
    }

    protected function tearDown(): void
    {
        foreach ($this->usersParent as $user) {
            $user->delete();
        }
        foreach ($this->users1 as $user) {
            $user->delete();
        }
        foreach ($this->users2 as $user) {
            $user->delete();
        }
        foreach ($this->companies as $company) {
            $company->delete();
        }
        $this->companyParent->delete();

        parent::tearDown();
    }
}
