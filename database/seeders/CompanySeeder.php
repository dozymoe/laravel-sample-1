<?php

namespace Database\Seeders;

use Datetime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = new Datetime;

        DB::table('companies')->upsert(
            [
                [
                    'name' => 'PT. XYZ',
                    'parent_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'PT. XYZ-1',
                    'parent_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'PT. XYZ-2',
                    'parent_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ],
            'name');
        DB::update(
            'update companies c1 inner join ' .
            '(select id from companies where name=?) as c2 ' .
            'set c1.parent_id=c2.id where name=?',
            ['PT. XYZ', 'PT. XYZ-1']);
        DB::update(
            'update companies c1 inner join ' .
            '(select id from companies where name=?) as c2 ' .
            'set c1.parent_id=c2.id where name=?',
            ['PT. XYZ', 'PT. XYZ-2']);
    }
}
