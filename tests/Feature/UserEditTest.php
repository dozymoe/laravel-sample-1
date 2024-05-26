<?php

namespace Tests\Feature;

use App\Models\User;
use Symfony\Component\DomCrawler\Crawler;
use Tests\CompanyUserTestCase;

class UserEditTest extends CompanyUserTestCase
{
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
            $this->admin_post_edit_user($user, $object, false);
        }
        foreach ($this->users1 as $object) {
            $this->admin_visit_edit_user($user, $object, true);
            $this->admin_post_edit_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->admin_visit_edit_user($user, $object, true);
            $this->admin_post_edit_user($user, $object, true);
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
            $this->manager_post_edit_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->manager_visit_edit_user($user, $object, true);
            $this->manager_post_edit_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->manager_visit_edit_user($user, $object, true);
            $this->manager_post_edit_user($user, $object, true);
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
            $this->admin_post_edit_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->admin_visit_edit_user($user, $object, false);
            $this->admin_post_edit_user($user, $object, false);
        }
        foreach ($this->users2 as $object) {
            $this->admin_visit_edit_user($user, $object, true);
            $this->admin_post_edit_user($user, $object, true);
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
            $this->supervisor_post_edit_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->supervisor_visit_edit_user($user, $object, true);
            $this->supervisor_post_edit_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->supervisor_visit_edit_user($user, $object, true);
            $this->supervisor_post_edit_user($user, $object, true);
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
            $this->user_visit_edit_user($user, $object, true);
            $this->user_post_edit_user($user, $object, true);
        }
        foreach ($this->users1 as $object) {
            $this->user_visit_edit_user($user, $object, true);
            $this->user_post_edit_user($user, $object, true);
        }
        foreach ($this->users2 as $object) {
            $this->user_visit_edit_user($user, $object, true);
            $this->user_post_edit_user($user, $object, true);
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

            return;
        } else {
            $response->assertStatus(200);
        }

        $dom = new Crawler($response->content());

        $inpName = $dom->filter('form.edit-user input[name="name"]');
        $this->assertEquals(1, $inpName->count());
        $this->assertEquals($object->name, $inpName->attr('value'));

        $inpEmail = $dom->filter('form.edit-user input[name="email"]');
        $this->assertEquals(1, $inpName->count());
        $this->assertEquals($object->email, $inpEmail->attr('value'));

        $optRole = $dom->filter(
            'form.edit-user select[name="role"] option[selected]');
        $this->assertEquals(1, $optRole->count());
        $this->assertContains($optRole->attr('value'), $object->getRoleNames());

        $btnSubmit = $dom->filter('form.edit-user button[type="submit"]');
        $this->assertEquals(1, $btnSubmit->count());
        $this->assertEquals('Update', $btnSubmit->text());

        $lnkBack = $dom->filter('form.edit-user a.go-back');
        $this->assertEquals(0, $lnkBack->count());
    }

    /**
     * Check edit user as admin
     */
    private function admin_post_edit_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->post("/user/{$object->id}/edit",
            [
                'name' => 'Hallo',
                'email' => $object->email,
                'role' => $object->getRoleNames()[0],
            ]);
        if ($expectFail) {
            $response->assertStatus(403);

            return;
        } else {
            $response->assertRedirectToRoute('dashboard');
        }

        $storedObject = User::where('id', $object->id)->first();
        $this->assertEquals('Hallo', $storedObject->name);
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
     * Check edit user as manager
     */
    private function manager_post_edit_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->post("/user/{$object->id}/edit",
            ['name' => 'Hallo']);
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
     * Check edit user as supervisor
     */
    private function supervisor_post_edit_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->post("/user/{$object->id}/edit",
            ['name' => 'Hallo']);
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

    /**
     * Check edit user as user
     */
    private function user_post_edit_user($user, $object, $expectFail): void
    {
        $response = $this->actingAs($user)->post("/user/{$object->id}/edit",
            ['name' => 'Hallo']);
        if ($expectFail) {
            $response->assertStatus(403);
        } else {
            $response->assertStatus(200);
        }
    }
}
