<?php

namespace App\Http\Controllers\Admin;

use App\BlogArticle;
use App\Group;
use App\GroupUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Journal;
use App\Note;
use App\Prayer;
use App\User;
use App\UsersViews;
use App\WallPost;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class ReportsController extends Controller
{
    private $dateFrom;
    private $dateTo;
    private $viewDateFrom;
    private $viewDateTo;
    private $searchFilter;
    private $planType;
    private $answeredPrayers;

    private function prepareFilters($model)
    {
        $this->dateFrom = Request::input('date_from', false);
        $this->dateTo = Request::input('date_to', false);
        $this->viewDateFrom = Request::input('view_date_from', false);
        $this->viewDateTo = Request::input('view_date_to', false);
        $this->searchFilter = Request::input('search', false);
        $this->planType = Request::input('plan_type', false);
        $this->answeredPrayers = Request::input('answered', false);

        if (!empty($this->dateFrom)) {
            $model->whereRaw('created_at >= to_timestamp(' . strtotime($this->dateFrom . " 00:00:00") . ")");
        }

        if (!empty($this->dateTo)) {
            $model->whereRaw('created_at <= to_timestamp(' . strtotime($this->dateTo . " 23:59:59") . ")");
        }

        if (!empty($this->viewDateFrom)) {
            $model->where(function ($q) {
                $q->orWhereRaw('created_at >= to_timestamp(' . strtotime($this->viewDateFrom . " 00:00:00") . ")");
                $q->orWhereRaw('updated_at >= to_timestamp(' . strtotime($this->viewDateFrom . " 00:00:00") . ")");
            });
        }

        if (!empty($this->viewDateTo)) {
            $model->where(function ($q) {
                $q->orWhereRaw('created_at <= to_timestamp(' . strtotime($this->viewDateTo . " 23:59:59") . ")");
                $q->orWhereRaw('updated_at <= to_timestamp(' . strtotime($this->viewDateTo . " 23:59:59") . ")");
            });
        }

        if (!empty($this->searchFilter)) {
            $model->where('name', 'ilike', '%' . $this->searchFilter . '%');
        }

        if (!empty($this->planType)) {
            $model->where('plan_type', $this->planType);
        }

        if ($this->answeredPrayers != '') {
            $model->where('answered', $this->answeredPrayers);
        }

        return $model;
    }

    public function index()
    {
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
            $q->whereNotIn('slug', [Config::get('app.role.admin')]);
});
        $users = $this->prepareFilters($users);
        $userIds = $users->lists('id')->toArray();
        $content['users'] = $users->paginate(20);

        $content['notesCount'] = Note::whereIn('user_id', $userIds)->count();
        $content['journalsCount'] = Journal::whereIn('user_id', $userIds)->count();
        $content['prayersCount'] = Prayer::whereIn('user_id', $userIds)->count();
        $content['answeredPrayersCount'] = Prayer::whereIn('user_id', $userIds)->where('answered', true)->count();
        $content['statusesCount'] = WallPost::whereIn('user_id', $userIds)->count();
        $content['groupsCount'] = Group::whereIn('owner_id', $userIds)->count();
        $content['groupJoinsCount'] = GroupUser::whereIn('user_id', $userIds)->count();
        $content['readerViewsCount'] = UsersViews::whereIn('user_id', $userIds)->where('item_category', UsersViews::CAT_READER)->count();
        $content['lexiconViewsCount'] = UsersViews::whereIn('user_id', $userIds)->where('item_category', UsersViews::CAT_LEXICON)->count();
        $content['strongsViewsCount'] = UsersViews::whereIn('user_id', $userIds)->where('item_category', UsersViews::CAT_STRONGS)->count();
        $content['blogViewsCount'] = UsersViews::whereIn('user_id', $userIds)->where('item_category', UsersViews::CAT_BLOG)->count();
        $content['referredUsersCount'] = User::whereIn('invited_by_id', $userIds)/*->where('invited_by_id','>',0)*/->count();

        return view(
            'admin.reports.main',
            [
                'page_title' => 'User Reports',
                'content' => $content,
            //                'id' => $id,
            ]
        );
    }

    public function getUserNotes($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $user = User::find($id);
        $userNotes = $user->notes();
        $userNotes = $this->prepareFilters($userNotes);
        $content['userNotes'] = $userNotes->orderBy('notes.created_at', 'DESC')->paginate(20);
        return view(
            'admin.reports.user-notes',
            [
                'page_title' => 'User "'.$user->name.'" Notes',
                'content' => $content,
                'id' => $id,
            ]
        );
    }

    public function getUserJournals($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $user = User::find($id);
        $userJournals = $user->journals();
        $userJournals = $this->prepareFilters($userJournals);
        $content['userJournals'] = $userJournals->orderBy('journal.created_at', 'DESC')->paginate(20);
        return view(
            'admin.reports.user-journals',
            [
                'page_title' => 'User "'.$user->name.'" Journals',
                'content' => $content,
                'id' => $id,
            ]
        );
    }

    public function getUserPrayers($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $user = User::find($id);
        $userPrayers = $user->prayers();
        $userPrayers = $this->prepareFilters($userPrayers);
        $content['userPrayers'] = $userPrayers->orderBy('prayers.created_at', 'DESC')->paginate(20);
        return view(
            'admin.reports.user-prayers',
            [
                'page_title' => 'User "'.$user->name.'" Prayers',
                'content' => $content,
                'id' => $id,
            ]
        );
    }

    public function getUserStatusUpdates($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $user = User::find($id);
        $userStatuses = $user->statuses();
        $userStatuses = $this->prepareFilters($userStatuses);
        $content['userStatuses'] = $userStatuses->orderBy('wall_posts.created_at', 'DESC')->paginate(20);
        return view(
            'admin.reports.user-statuses',
            [
                'page_title' => 'User "'.$user->name.'" Status Updates',
                'content' => $content,
                'id' => $id,
            ]
        );
    }

    public function getUserGroups($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $type = Input::get('type', 'created');
        $user = User::find($id);
        if ($type == 'joined') {
            $userGroups = $user->joinedGroups(true);
        } else {
            $userGroups = $user->myGroups();
        }

        $userGroups = $this->prepareFilters($userGroups);
        $content['userGroups'] = $userGroups->orderBy('groups.created_at', 'DESC')->paginate(20);
        return view(
            'admin.reports.user-groups',
            [
                'page_title' => 'User "'.$user->name.'" Groups',
                'content' => $content,
                'id' => $id,
            ]
        );
    }

    public function getUserReaderPages($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $user = User::find($id);
        $userViews = $user->readerViews();
        $userViews = $this->prepareFilters($userViews);
        $content['userViews'] = $userViews->orderBy('updated_at', 'DESC')->paginate(20);
        return view(
            'admin.reports.user-reader-pages',
            [
                'page_title' => 'User "'.$user->name.'" Reader Pages Views',
                'content' => $content,
                'id' => $id,
            ]
        );
    }

    public function getUserLexiconPages($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $user = User::find($id);
        $userViews = $user->lexiconViews();
        $userViews = $this->prepareFilters($userViews);
        $content['userViews'] = $userViews->orderBy('updated_at', 'DESC')->paginate(20);
        return view(
            'admin.reports.user-lexicon-pages',
            [
                'page_title' => 'User "'.$user->name.'" Lexicon Pages Views',
                'content' => $content,
                'id' => $id,
            ]
        );
    }

    public function getUserStrongsPages($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $user = User::find($id);
        $userViews = $user->strongsViews();
        $userViews = $this->prepareFilters($userViews);
        $content['userViews'] = $userViews->orderBy('updated_at', 'DESC')->paginate(20);
        return view(
            'admin.reports.user-strongs-pages',
            [
                'page_title' => 'User "'.$user->name.'" Strongs Pages Views',
                'content' => $content,
                'id' => $id,
            ]
        );
    }

    public function getUserBlogPages($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $user = User::find($id);
        $userViews = $user->blogViews();
        $userViews = $this->prepareFilters($userViews);
        $content['userViews'] = $userViews->orderBy('updated_at', 'DESC')->paginate(20);
        return view(
            'admin.reports.user-blog-pages',
            [
                'page_title' => 'User "'.$user->name.'" Blog Pages Views',
                'content' => $content,
                'id' => $id,
            ]
        );
    }

    public function getReferredUsers($id)
    {
        Session::flash('backUrl', Request::fullUrl());
        $users = User::query()->where('invited_by_id', $id);
        $users = $this->prepareFilters($users);
        $content['users'] = $users->orderBy('users.created_at', 'DESC')->paginate(20);
        return view(
            'admin.reports.referred-people',
            [
                'page_title' => 'Users',
                'content' => $content,
                'id' => $id,
            ]
        );
    }
}
