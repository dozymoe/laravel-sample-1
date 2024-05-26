<?php

namespace Tests\Feature;

use Symfony\Component\DomCrawler\Crawler;
use Tests\CompanyUserTestCase;

class DashboardTest extends CompanyUserTestCase
{
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
            $this->assertEquals(
                $this->now->setTimezone(config('app.usertz'))->format('Y-m-d H:i:s'),
                $fourthCol->text());

            $fifthCol = $currentRow->filter('td:nth-child(5)');

            $editLink = $fifthCol->filter('a:nth-child(1)');
            $this->assertEquals('Edit', $editLink->text());
            $this->assertStringStartsWith(
                route('user.update', ['object' => $object]),
                $editLink->attr('href'));

            $deleteLink = $fifthCol->filter('a:nth-child(2)');
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
            $this->assertEquals(
                $this->now->setTimezone(config('app.usertz'))->format('Y-m-d H:i:s'),
                $fourthCol->text());

            $fifthCol = $currentRow->filter('td:nth-child(5)');
            $this->assertEquals('', $fifthCol->text());
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
            $this->assertEquals(
                $this->now->setTimezone(config('app.usertz'))->format('Y-m-d H:i:s'),
                $fourthCol->text());

            $fifthCol = $currentRow->filter('td:nth-child(5)');
            $this->assertEquals('', $fifthCol->text());
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
