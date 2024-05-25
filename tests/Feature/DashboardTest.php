<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

//use Spatie\Permission\Models\Role;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->companyParent = Company::factory()->create();
        $this->companies = Company::factory()->state([
            'parent_id' => $this->companyParent->id,
        ])
            ->count(2)->create();

        $this->usersParent = User::factory()->state([
            'company_id' => $this->companyParent->id,
        ])
            ->count(2)->create();
        $this->usersParent[0]->assignRole('admin');
        $this->usersParent[1]->assignRole('manager');

        $this->users1 = User::factory()->state([
            'company_id' => $this->companies[0]->id,
        ])
            ->count(30)->create();
        $this->users1[0]->assignRole('admin');
        $this->users1[1]->assignRole('supervisor');
        foreach ($this->users1->slice(2) as $user) {
            $user->assignRole('user');
        }

        $this->users2 = User::factory()->state([
            'company_id' => $this->companies[1]->id,
        ])
            ->count(30)->create();
        $this->users2[0]->assignRole('admin');
        $this->users2[1]->assignRole('supervisor');
        foreach ($this->users2->slice(2) as $user) {
            $user->assignRole('user');
        }
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

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function admin_parent_company_visit_dashboard(): void
    {
        // parent company admin
        $this->admin_visit_dashboard($this->usersParent[0], $this->usersParent);
    }

    /**
     * @test
     */
    public function manager_parent_company_visit_dashboard(): void
    {
        // parent company manager
        $this->manager_visit_dashboard($this->usersParent[1]);
    }

    /**
     * @test
     */
    public function admin_company_visit_dashboard(): void
    {
        // company1 admin
        $this->admin_visit_dashboard($this->users1[0], $this->users1);
    }

    /**
     * @test
     */
    public function supervisor_company_visit_dashboard(): void
    {
        // company1 supervisor
        $this->supervisor_visit_dashboard($this->users1[1], $this->users1);
    }

    /**
     * @test
     */
    public function user_company_visit_dashboard(): void
    {
        // company1 user
        $this->normaluser_visit_dashboard($this->users1[2]);
    }

    /**
     * Check dashboard as admin
     */
    private function admin_visit_dashboard($user, $objects): void
    {
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        $dom = new Crawler($response->content());

        $trNodes = $dom->filter('table.table tbody tr');
        $this->assertEquals($objects->count(), $trNodes->count());
    }

    /**
     * Check dashboard as manager
     */
    private function manager_visit_dashboard($user): void
    {
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        $dom = new Crawler($response->content());

        $trNodes = $dom->filter('table.table tbody tr');
        $this->assertEquals(2, $trNodes->count());
    }

    /**
     * Check dashboard as supervisor
     */
    private function supervisor_visit_dashboard($user, $objects): void
    {
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        $dom = new Crawler($response->content());

        $trNodes = $dom->filter('table.table tbody tr');
        $this->assertEquals($objects->count() - 2, $trNodes->count());
    }

    /**
     * Check dashboard as user
     */
    private function normaluser_visit_dashboard($user): void
    {
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        $dom = new Crawler($response->content());

        $trNodes = $dom->filter('table.table tbody tr');
        $this->assertEquals(0, $trNodes->count());
    }
}
