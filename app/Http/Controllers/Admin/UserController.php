<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ViewHelper;
use App\User;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Kodeine\Acl\Models\Eloquent\Role;
use Krucas\Notification\Facades\Notification;
use Validator;

class UserController extends Controller
{
    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());
        $role = Request::input('role', 0);
        $users = User::query()
            ->with('roles');
        if ($role) {
            $users->whereHas('roles', function ($q) use ($role) {
                    $q->where('slug', $role);
            });
        }
        $content['users'] = $users->with('invited')->orderBy('users.created_at', 'DESC')->paginate(20);
        return view(
            'admin.user.list',
            [
                'page_title' => 'Users',
                'content' => $content,
                'filterAction' => 'user/list/',
            ]
        );
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        $model = new User();
        $roles = Role::all()->toArray();
        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            if ($model->create(Input::all())) {
                if ((Input::get('plan_type') == User::PLAN_PREMIUM) && Input::get('plan_name')) {
                    $model->upgradeToPremium(Input::get('plan_name'));
                } else {
                    $model->downgradeToFree();
                    $model->createAccountAndOrSubscribe();
                }
                Notification::success('User has been successfully created');
            }
            return Redirect::to(ViewHelper::adminUrlSegment().'/user/list/');
        }
        return view(
            'admin.user.create',
            [
                'model' => $model,
                'page_title' => 'Create New User',
                'roles' => ViewHelper::prepareForSelectBox($roles, 'slug', 'name')
            ]
        );
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = User::query()->with('roles')->find($id);
        $roles = Role::all()->toArray();
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            if ($model->update(Input::all())) {
                if ((Input::get('plan_type') == User::PLAN_PREMIUM) && Input::get('plan_name')) {
                    $model->upgradeToPremium(Input::get('plan_name'));
                } else {
                    $model->downgradeToFree();
                    $model->createAccountAndOrSubscribe();
                }
                Notification::success('User has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment().'/user/list/');
        }
        return view(
            'admin.user.update',
            [
                'model' => $model,
                'page_title' => 'Edit User',
                'roles' => ViewHelper::prepareForSelectBox($roles, 'slug', 'name')
            ]
        );
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        if (User::destroy($id)) {
            Notification::success('User has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment().'/user/list/');
    }

    public function getAuthorize($id)
    {
        $user = User::query()->find($id);
        $admin = User::find(Auth::user()->id);
        $admin->auth_token = str_random(40);
        $admin->save();
        Auth::login($user, $remember = false);
        if (!$adminBackUrl = Session::get('backUrl')) {
            $adminBackUrl = ViewHelper::adminUrlSegment().'/user/list/';
        }
        Session::put('adminAsUser', true);
        Session::put('adminId', $admin->id);
        Session::put('adminBackUrl', $adminBackUrl);
        Session::put('adminAuthToken', $admin->auth_token);
        return Redirect::to('/');
    }

    public function getGetUsersByRole()
    {
        $role = Request::input('role', 0);

        $users = User::whereHas('roles', function ($q) use ($role) {
            $q->whereIn('slug', [$role]);
        })->get()->pluck('name', 'id');
        $users->toArray();
        $result = [];
        if (count($users)) {
            foreach ($users as $key => $user) {
                $result[] = ['id' => $key,'text'=>$user];
            }
        }
        return $users;
    }
}
