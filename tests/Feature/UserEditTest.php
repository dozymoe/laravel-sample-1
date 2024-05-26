<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

//use Symfony\Component\DomCrawler\Crawler;

class UserEditTest extends TestCase
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
            ->count(4)->create();
        $this->users1[0]->assignRole('admin');
        $this->users1[1]->assignRole('supervisor');
        foreach ($this->users1->slice(2) as $user) {
            $user->assignRole('user');
        }

        $this->users2 = User::factory()->state([
            'company_id' => $this->companies[1]->id,
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

    /**
     * @test
     */
    public function anon_visit_edit_user(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
        $response->assertRedirectToRoute('login');
    }

    /**
     * @test
     */
    public function admin_parent_company_visit_edit_user(): void
    {
        // parent company admin
        $user = $this->usersParent[0];

        foreach ($this->usersParent as $object) {
            $this->admin_visit_edit_user($user, $object, false);
        }
        foreach ($this->users1 as $object) {
            $this->admin_visit_edit_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->admin_visit_edit_user($user, $object, true);
        }
    }

    /**
     * @test
     */
    public function manager_parent_company_visit_edit_user(): void
    {
        // parent company manager
        $user = $this->usersParent[1];

        foreach ($this->usersParent as $object) {
            $this->manager_visit_edit_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->manager_visit_edit_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->manager_visit_edit_user($user, $object, true);
        }
    }

    /**
     * @test
     */
    public function admin_company_visit_edit_user(): void
    {
        // company1 admin
        $user = $this->users1[0];

        foreach ($this->usersParent as $object) {
            $this->admin_visit_edit_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->admin_visit_edit_user($user, $object, false);
        }
        foreach ($this->users2 as $object) {
            $this->admin_visit_edit_user($user, $object, true);
        }
    }

    /**
     * @test
     */
    public function supervisor_company_visit_edit_user(): void
    {
        // company1 supervisor
        $user = $this->users1[1];

        foreach ($this->usersParent as $object) {
            $this->supervisor_visit_edit_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->supervisor_visit_edit_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->supervisor_visit_edit_user($user, $object, true);
        }
    }

    /**
     * @test
     */
    public function user_company_visit_edit_user(): void
    {
        // company1 supervisor
        $user = $this->users1[2];

        foreach ($this->usersParent as $object) {
            $this->supervisor_visit_edit_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->supervisor_visit_edit_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->supervisor_visit_edit_user($user, $object, true);
        }
    }

    /**
     * Check edit user as admin
     */
    private function admin_visit_edit_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->get("/user/{$object->id}/edit");
        if ($expectFail) {
            $response->assertStatus(403);
        } else {
            $response->assertStatus(200);
        }
    }

    /**
     * Check edit user as manager
     */
    private function manager_visit_edit_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->get("/user/{$object->id}/edit");
        if ($expectFail) {
            $response->assertStatus(403);
        } else {
            $response->assertStatus(200);
        }
    }

    /**
     * Check edit user as supervisor
     */
    private function supervisor_visit_edit_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->get("/user/{$object->id}/edit");
        if ($expectFail) {
            $response->assertStatus(403);
        } else {
            $response->assertStatus(200);
        }
    }

    /**
     * Check edit user as user
     */
    private function user_visit_edit_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->get("/user/{$object->id}/edit");
        if ($expectFail) {
            $response->assertStatus(403);
        } else {
            $response->assertStatus(200);
        }
    }
}
