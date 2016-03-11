<?php

use App\BooksListEn;
use App\User;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Role;

class BasicRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Create User type and role */

        $userRole = 'user';
        $user = User::query()->where('email','user@user.com')->first();
        if($user){
            $user->revokeAllRoles();
            $user->delete();
        }
        $role = Role::query()->where('slug',$userRole);
        if($role){
            $role->delete();
        }

        $user = new User();
        $user->name = 'User';
        $user->email = 'user@user.com';
        $user->password = bcrypt('Aa123654');
        $user->save();

        $role = new Role();
        $role->name = 'User';
        $role->slug = $userRole;
        $role->description = 'basic user functionality';
        $role->save();

        $user->assignRole($userRole);

        /* Create Teacher type and role */

        $teacherRole = 'teacher';
        $teacher = User::query()->where('email','teacher@teacher.com')->first();
        if($teacher){
            $teacher->revokeAllRoles();
            $teacher->delete();
        }
        $role = Role::query()->where('slug',$teacherRole);
        if($role){
            $role->delete();
        }

        $teacher = new User();
        $teacher->name = 'Teacher';
        $teacher->email = 'teacher@teacher.com';
        $teacher->password = bcrypt('Aa123654');
        $teacher->save();

        $role = new Role();
        $role->name = 'Teacher';
        $role->slug = $teacherRole;
        $role->description = 'basic teacher functionality';
        $role->save();

        $user->assignRole($teacherRole);
    }
}
