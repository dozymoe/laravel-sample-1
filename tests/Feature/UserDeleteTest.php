<?php

namespace Tests\Feature;

use Symfony\Component\DomCrawler\Crawler;
use Tests\CompanyUserTestCase;

class UserDeleteTest extends CompanyUserTestCase
{
    /**
     * @test
     */
    public function anon_visit_delete_user(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
        $response->assertRedirectToRoute('login');
    }

    /**
     * @test
     */
    public function admin_parent_company_visit_delete_user(): void
    {
        // parent company admin
        $user = $this->usersParent[0];

        foreach ($this->usersParent as $object) {
            $this->admin_visit_delete_user($user, $object, false);
        }
        foreach ($this->users1 as $object) {
            $this->admin_visit_delete_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->admin_visit_delete_user($user, $object, true);
        }
    }

    /**
     * @test
     */
    public function manager_parent_company_visit_delete_user(): void
    {
        // parent company manager
        $user = $this->usersParent[1];

        foreach ($this->usersParent as $object) {
            $this->manager_visit_delete_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->manager_visit_delete_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->manager_visit_delete_user($user, $object, true);
        }
    }

    /**
     * @test
     */
    public function admin_company_visit_delete_user(): void
    {
        // company1 admin
        $user = $this->users1[0];

        foreach ($this->usersParent as $object) {
            $this->admin_visit_delete_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->admin_visit_delete_user($user, $object, false);
        }
        foreach ($this->users2 as $object) {
            $this->admin_visit_delete_user($user, $object, true);
        }
    }

    /**
     * @test
     */
    public function supervisor_company_visit_delete_user(): void
    {
        // company1 supervisor
        $user = $this->users1[1];

        foreach ($this->usersParent as $object) {
            $this->supervisor_visit_delete_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->supervisor_visit_delete_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->supervisor_visit_delete_user($user, $object, true);
        }
    }

    /**
     * @test
     */
    public function user_company_visit_delete_user(): void
    {
        // company1 supervisor
        $user = $this->users1[2];

        foreach ($this->usersParent as $object) {
            $this->supervisor_visit_delete_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->supervisor_visit_delete_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->supervisor_visit_delete_user($user, $object, true);
        }
    }

    /**
     * Check delete user as admin
     */
    private function admin_visit_delete_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->get("/user/{$object->id}/delete");
        if ($expectFail) {
            $response->assertStatus(403);

            return;
        } else {
            $response->assertStatus(200);
        }

        $dom = new Crawler($response->content());

        $btnSubmit = $dom->filter('form.delete-user button[type="submit"]');
        $this->assertEquals(1, $btnSubmit->count());
        $this->assertEquals('Delete', $btnSubmit->text());

        $lnkBack = $dom->filter('form.delete-user a.go-back');
        $this->assertEquals(0, $lnkBack->count());
    }

    /**
     * Check delete user as manager
     */
    private function manager_visit_delete_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->get("/user/{$object->id}/delete");
        if ($expectFail) {
            $response->assertStatus(403);
        } else {
            $response->assertStatus(200);
        }
    }

    /**
     * Check delete user as supervisor
     */
    private function supervisor_visit_delete_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->get("/user/{$object->id}/delete");
        if ($expectFail) {
            $response->assertStatus(403);
        } else {
            $response->assertStatus(200);
        }
    }

    /**
     * Check delete user as user
     */
    private function user_visit_delete_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->get("/user/{$object->id}/delete");
        if ($expectFail) {
            $response->assertStatus(403);
        } else {
            $response->assertStatus(200);
        }
    }
}
