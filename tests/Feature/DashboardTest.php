<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

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

    /**
     * @test
     */
    public function anon_visit_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
        $response->assertRedirectToRoute('login');
    }

    /**
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
        $this->manager_visit_dashboard($this->usersParent[1], $this->supervisors);
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
        $this->user_visit_dashboard($this->users1[2]);
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

        foreach ($trNodes as $row) {
            $currentRow = new Crawler($row);
            $firstCol = $currentRow->filter('td:nth-child(1)');

            // find user in our list
            $object = $objects->firstWhere('name', $firstCol->text());
            $this->assertNotNull($object);

            $secondCol = $currentRow->filter('td:nth-child(2)');
            $this->assertEquals($object->company->name, $secondCol->text());
            // must be from same company
            $this->assertEquals($user->company->name, $secondCol->text());

            $thirdCol = $currentRow->filter('td:nth-child(3)');
            $this->assertContains(
                $thirdCol->text(),
                ['admin', 'manager', 'supervisor', 'user']);

            $fourthCol = $currentRow->filter('td:nth-child(4)');

            $editLink = $fourthCol->filter('a:nth-child(1)');
            $this->assertEquals('Edit', $editLink->text());
            $this->assertStringStartsWith(
                route('user.update', ['object' => $object]),
                $editLink->attr('href'));

            $deleteLink = $fourthCol->filter('a:nth-child(2)');
            $this->assertEquals('Delete', $deleteLink->text());
            $this->assertStringStartsWith(
                route('user.delete', ['object' => $object]),
                $deleteLink->attr('href'));
        }
    }

    /**
     * Check dashboard as manager
     */
    private function manager_visit_dashboard($user, $objects): void
    {
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        $dom = new Crawler($response->content());

        $trNodes = $dom->filter('table.table tbody tr');
        $this->assertEquals($objects->count(), $trNodes->count());

        foreach ($trNodes as $row) {
            $currentRow = new Crawler($row);
            $firstCol = $currentRow->filter('td:nth-child(1)');

            // find user in our list
            $object = $objects->firstWhere('name', $firstCol->text());
            $this->assertNotNull($object);

            $secondCol = $currentRow->filter('td:nth-child(2)');
            $this->assertEquals($object->company->name, $secondCol->text());

            $thirdCol = $currentRow->filter('td:nth-child(3)');
            $this->assertContains(
                $thirdCol->text(),
                ['supervisor']);

            $fourthCol = $currentRow->filter('td:nth-child(4)');
            $this->assertEquals('', $fourthCol->text());
        }
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

        foreach ($trNodes as $row) {
            $currentRow = new Crawler($row);
            $firstCol = $currentRow->filter('td:nth-child(1)');

            // find user in our list
            $object = $objects->firstWhere('name', $firstCol->text());
            $this->assertNotNull($object);

            $secondCol = $currentRow->filter('td:nth-child(2)');
            $this->assertEquals($object->company->name, $secondCol->text());
            // must be from same company
            $this->assertEquals($user->company->name, $secondCol->text());

            $thirdCol = $currentRow->filter('td:nth-child(3)');
            $this->assertContains(
                $thirdCol->text(),
                ['user']);

            $fourthCol = $currentRow->filter('td:nth-child(4)');
            $this->assertEquals('', $fourthCol->text());
        }
    }

    /**
     * Check dashboard as user
     */
    private function user_visit_dashboard($user): void
    {
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        $dom = new Crawler($response->content());

        $trNodes = $dom->filter('table.table tbody tr');
        $this->assertEquals(0, $trNodes->count());
    }
}
