<?php

namespace App\Http\Controllers\Admin;


use App\BlogArticle;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Note;
use App\User;
use App\UsersViews;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class ReportsController extends Controller
{
    private $dateFrom;
    private $dateTo;
    private $searchFilter;
    private $planType;

    private function prepareFilters($model)
    {
        $this->dateFrom = Request::input('date_from', false);
        $this->dateTo = Request::input('date_to', false);
        $this->searchFilter = Request::input('search', false);
        $this->planType = Request::input('plan_type', false);

        if (!empty($this->dateFrom)) {
            $model->whereRaw('created_at >= to_timestamp(' . strtotime($this->dateFrom . " 00:00:00") . ")");
        }

        if (!empty($this->dateTo)) {
            $model->whereRaw('created_at <= to_timestamp(' . strtotime($this->dateTo . " 23:59:59") . ")");
        }

        if (!empty($this->searchFilter)) {
            $model->where('name', 'ilike', '%' . $this->searchFilter . '%');
        }

        if (!empty($this->planType)) {
            $model->where('plan_type', $this->planType);
        }

        return $model;
    }

    public function index(){
        $users = User::selectRaw('
            users.*,
            (SELECT count(*) FROM users as invites WHERE invites.invited_by_id = users.id) as invites_count,
            (SELECT count(*) FROM notes WHERE users.id = notes.user_id) as notes_count,
            (SELECT count(*) FROM journal WHERE users.id = journal.user_id) as journal_count,
            (SELECT count(*) FROM prayers WHERE users.id = prayers.user_id) as prayers_count,
            (SELECT count(*) FROM prayers WHERE users.id = prayers.user_id AND prayers.answered = TRUE) as answered_prayers_count,
            (SELECT count(*) FROM wall_posts WHERE users.id = wall_posts.user_id) as statuses_count,

            (SELECT count(*) FROM groups WHERE users.id = groups.owner_id) as created_groups_count,
            (SELECT count(*) FROM groups_users WHERE users.id = groups_users.user_id) as joined_groups_count,

            (SELECT count(*) FROM users_views WHERE users.id = users_views.user_id AND item_category = \''.UsersViews::CAT_READER.'\') as reader_views_count,
            (SELECT count(*) FROM users_views WHERE users.id = users_views.user_id AND item_category = \''.UsersViews::CAT_LEXICON.'\') as lexicon_views_count,
            (SELECT count(*) FROM users_views WHERE users.id = users_views.user_id AND item_category = \''.UsersViews::CAT_STRONGS.'\') as strongs_views_count,
            (SELECT count(*) FROM users_views WHERE users.id = users_views.user_id AND item_category = \''.UsersViews::CAT_BLOG.'\') as blog_views_count

        ')->whereHas('roles', function ($q) {
            $q->whereNotIn('slug',[Config::get('app.role.admin')]);
        });
        $users = $this->prepareFilters($users);
        $content['users'] = $users->paginate(20);
        return view('admin.reports.main',
            [
                'page_title' => 'User Reports',
                'content' => $content,
//                'id' => $id,
            ]);
    }

    public function getUserNotes($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $user = User::find($id);
        $userNotes = $user->notes();
        $userNotes = $this->prepareFilters($userNotes);
        $content['userNotes'] = $userNotes->orderBy('notes.created_at','DESC')->paginate(20);
        return view('admin.reports.user-notes',
            [
                'page_title' => 'User "'.$user->name.'" Notes',
                'content' => $content,
                'id' => $id,
            ]);
    }
}
