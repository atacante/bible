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
use Validator;


class UserController extends Controller
{
    private function validator($request,$user)
    {
        $rules = [
            'name' => 'required|max:255',
            'role' => 'required',
//            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
        ];

        switch(Request::method())
        {
            case 'PUT':
            {
                $rules['email'] = 'required|email|max:255|unique:users,email,'.$user->id;
            }
            break;
            case 'POST':
            /*{
                $rules['password'] = 'required|confirmed|min:6';
                $rules['password_confirmation'] = 'required';
            }
            break;*/
            case 'GET':
            case 'DELETE':
            default:
            break;
        }
        $this->validate($request, $rules);
    }

    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());
        $content['users'] = User::query()
            ->with('roles')
            ->orderBy('created_at','DESC')
            ->paginate(20);
        return view('admin.user.list',
            [
                'page_title' => 'Users',
                'content' => $content
            ]);
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        $model = new User();
        $roles = Role::all()->toArray();
        if (Request::isMethod('post')) {
            $this->validator($request,$model);
            $model->create(Input::all());
            return Redirect::to('/admin/user/list/');
        }
        return view('admin.user.create',
            [
                'model' => $model,
                'page_title' => 'Create New User',
                'roles' => ViewHelper::prepareForSelectBox($roles,'slug','name')
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = User::query()->with('roles')->find($id);
        $roles = Role::all()->toArray();
        if (Request::isMethod('put')) {
            $this->validator($request,$model);
            $model->update(Input::all());
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to('/admin/user/list/');
        }
//        var_dump($model);exit;
        return view('admin.user.update',
            [
                'model' => $model,
                'page_title' => 'Edit User',
                'roles' => ViewHelper::prepareForSelectBox($roles,'slug','name')
            ]);
    }
    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        User::destroy($id);
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to('/admin/user/list/');
    }
}
