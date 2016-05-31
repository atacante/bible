<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;

use App\Coupon;
use App\Helpers\ViewHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;

class CouponsController extends Controller
{
    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());

        $couponsModel = new Coupon();

        $content['coupons'] = $couponsModel->query()->orderBy('id')->paginate(20);
        return view('admin.coupons.list',
            [
                'page_title' => 'Coupons',
                'content' => $content,
                'filterAction' => 'coupons/list/',
            ]);
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        $model = new Coupon();
        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['expire_at'] = $data['expire_at']?strtotime($data['expire_at']):null;
            if ($model = $model->create($data)) {
                Notification::success('Coupon has been successfully created');
            }
            return Redirect::to(ViewHelper::adminUrlSegment() . '/coupons/list/');
        }

        return view('admin.coupons.create',
            [
                'model' => $model,
                'page_title' => 'Create New Coupon',
                'users' => User::whereHas('roles', function ($q)
                {
                    $q->whereNotIn('slug',[Config::get('app.role.admin')]);
                })->get()->pluck('name','id')->toArray()
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Coupon::query()->find($id);
        if (Request::isMethod('put')){
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['expire_at'] = $data['expire_at']?strtotime($data['expire_at']):null;
            if ($model->update($data)) {
                Notification::success('Coupons has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment() . '/coupons/list/');
        }
        return view('admin.coupons.update',
            [
                'model' => $model,
                'page_title' => 'Edit Coupon',
                'users' => User::whereHas('roles', function ($q)
                {
                    $q->whereNotIn('slug',[Config::get('app.role.admin')]);
                })->get()->pluck('name','id')->toArray()
            ]);
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $coupon = Coupon::query()->find($id);
        if ($coupon->delete()) {
            Notification::success('Coupon has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment() . '/coupons/list/');
    }

    public function anyGetCode()
    {
        return $this->generateCode();
    }

    private function generateCode(){
        $code = str_random(10);
        if(Coupon::where('coupon_code', $code)->exists()){
            $this->generateCode();
        }
        return $code;
    }
}
