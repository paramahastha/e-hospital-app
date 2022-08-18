<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Orchid\Platform\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('roles')->truncate();

        DB::table('roles')->insert([
            [
                'slug' => 'admin',
                'name' => 'Admin',
                'permissions' => '{"platform.main": "1", "platform.index": "1", "platform.systems.roles": "1", "platform.systems.users": "1", "platform.doctor.management": "1", "platform.systems.attachment": "1"}',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'slug' => 'doctor',
                'name' => 'Doctor',
                'permissions' => '{"platform.main": "1", "platform.index": "1", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.doctor.management": "0", "platform.systems.attachment": "0"}',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'slug' => 'staff',
                'name' => 'Staff',
                'permissions' => '{"platform.main": "1", "platform.index": "1", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.doctor.management": "1", "platform.systems.attachment": "0"}',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'slug' => 'patient',
                'name' => 'Patient',
                'permissions' => '{"platform.main": "0", "platform.index": "0", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.doctor.management": "0", "platform.systems.attachment": "0"}',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('users')->truncate();

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now()
        ]);  

        DB::table('role_users')->truncate();

        DB::table('role_users')->insert([
            'user_id' => '1',
            'role_id' => '1',            
        ]);  

        Schema::enableForeignKeyConstraints();
    }
}
