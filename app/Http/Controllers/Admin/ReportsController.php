<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class ReportsController extends Controller
{
    private $dateFrom;
    private $dateTo;

    private function prepareFilters($model)
    {
        $this->dateFrom = Request::input('date_from', false);
        $this->dateTo = Request::input('date_to', false);

        if (!empty($this->dateFrom)) {
            $model->whereRaw('created_at >= to_timestamp(' . strtotime($this->dateFrom . " 00:00:00") . ")");
        }

        if (!empty($this->dateTo)) {
            $model->whereRaw('created_at <= to_timestamp(' . strtotime($this->dateTo . " 23:59:59") . ")");
        }

        return $model;
    }

    public function index($id){

    }

    public function getReferredUsers($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $role = Request::input('role',0);
        $users = User::query()->where('invited_by_id',$id);
        $users = $this->prepareFilters($users);
        $content['users'] = $users->orderBy('users.created_at','DESC')->paginate(20);
        return view('admin.reports.referred-people',
            [
                'page_title' => 'Users',
                'content' => $content,
                'id' => $id,
            ]);
    }
}
