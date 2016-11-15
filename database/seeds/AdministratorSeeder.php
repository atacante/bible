<?php

use App\BooksListEn;
use App\User;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Console\Command;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Role;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Config::get('app.role.admin');
        $admin = User::query()->where('email', 'admin@admin.com')->first();
        if ($admin) {
            $admin->revokeRole($adminRole);
            $admin->revokeAllRoles();
            $admin->delete();
        }
        $role = Role::query()->where('slug', $adminRole);
        if ($role) {
            $role->delete();
        }

        $admin = new User();
        $admin->name = 'Administrator';
        $admin->email = 'admin@admin.com';
        $admin->password = bcrypt('Aa123654');
        $admin->save();

        $roleAdmin = new Role();
        $roleAdmin->name = 'Administrator';
        $roleAdmin->slug = $adminRole;
        $roleAdmin->description = 'manage administration privileges';
        $roleAdmin->save();

        $admin->assignRole($roleAdmin);
    }
}
