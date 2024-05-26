<?php

namespace Database\Seeders;

use Datetime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([CompanySeeder::class]);

        $companies = DB::select('select * from companies');
        $roleAdminId = DB::scalar('select id from roles where name=?',
            ['admin']);
        $roleManagerId = DB::scalar('select id from roles where name=?',
            ['manager']);
        $roleSupervisorId = DB::scalar('select id from roles where name=?',
            ['supervisor']);
        $roleUserId = DB::scalar('select id from roles where name=?',
            ['user']);
        $now = new Datetime;

        $values = [];
        foreach ($companies as $company) {
            $cleanHost = strtolower(preg_replace('/[.\s-]/', '', $company->name));
            $emailHost = '@' . $cleanHost . '.com';
            $password = Hash::make('pass');

            $values[] = [
                'email' => 'admin' . $emailHost,
                'name' => 'Admin ' . $company->name,
                'company_id' => $company->id,
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($company->parent_id) {
                // This is sub-company
                for ($ii = 1; $ii < 5; $ii++) {
                    $values[] = [
                        'email' => 'supervisor' . $ii . $emailHost,
                        'name' => 'Supervisor' . $ii . ' ' . $company->name,
                        'company_id' => $company->id,
                        'password' => $password,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                for ($ii = 1; $ii < 50; $ii++) {
                    $values[] = [
                        'email' => 'user' . $ii . $emailHost,
                        'name' => 'User' . $ii . ' ' . $company->name,
                        'company_id' => $company->id,
                        'password' => $password,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            } else {
                // This is parent company
                for ($ii = 1; $ii < 5; $ii++) {
                    $values[] = [
                        'email' => 'manager' . $ii . $emailHost,
                        'name' => 'Manager' . $ii . ' ' . $company->name,
                        'company_id' => $company->id,
                        'password' => $password,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        DB::table('users')->upsert($values, 'email');

        $values = [];
        foreach (DB::select('select * from users') as $user) {
            $row = ['model_type' => 'App\Models\User', 'model_id' => $user->id];

            if (Str::startsWith($user->email, 'user')) {
                $row['role_id'] = $roleUserId;
            } elseif (Str::startsWith($user->email, 'manager')) {
                $row['role_id'] = $roleManagerId;
            } elseif (Str::startsWith($user->email, 'supervisor')) {
                $row['role_id'] = $roleSupervisorId;
            } elseif (Str::startsWith($user->email, 'admin')) {
                $row['role_id'] = $roleAdminId;
            }

            $values[] = $row;
        }

        DB::table('model_has_roles')->upsert($values,
            ['role_id', 'model_type', 'model_id']);
    }
}
