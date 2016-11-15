<?php

namespace App\Http\Controllers\Admin;

use App\BaseModel;
use App\Http\Controllers\Controller;
use App\ContentReport;
use App\Helpers\ViewHelper;
//use Illuminate\Http\Request;

use App\Http\Requests;
use App\Journal;
use App\Note;
use App\Prayer;
use App\WallPost;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;

class ComplaintsController extends Controller
{
    private function prepareFilters($model)
    {
        $searchFilter = Input::get('search', false);
        $typeFilter = Input::get('type', false);
        $statusFilter = Input::get('status', false);

        if (!empty($searchFilter)) {
            $model->where('title', 'ilike', '%' . $searchFilter . '%');
        }

        if (!empty($typeFilter)) {
            $type = '';
            switch ($typeFilter) {
                case 'note':
                    $type = 'App\Note';
                    break;
                case 'journal':
                    $type = 'App\Journal';
                    break;
                case 'prayer':
                    $type = 'App\Prayer';
                    break;
                case 'status':
                    $type = 'App\WallPost';
                    break;
            }
            $model->where('item_type', $type);
        }

        if ($statusFilter != '') {
            $model->where('resolved', $statusFilter);
        }

        return $model;
    }

    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());

        $contentReportModel = ContentReport::query();

        $contentReportModel = $this->prepareFilters($contentReportModel);

        $content['complaints'] = $contentReportModel->with('item', 'user')->orderBy('resolved')->orderBy('created_at', 'desc')->paginate(20);
        return view(
            'admin.complaints.list',
            [
                'page_title' => 'Complaints',
                'content' => $content,
                'filterAction' => 'complaints/list/',
            ]
        );
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $type, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $contentReportModel = ContentReport::query()->find($id);

        $model = BaseModel::getWallItemModel($type, $contentReportModel->item_id);

        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            if ($model->update($data)) {
                $contentReportModel->resolved = true;
                $contentReportModel->save();
                Notification::success('Item has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment() . '/complaints/list/');
        }
        return view(
            'admin.complaints.update',
            [
                'itemModel' => $model,
                'contentReportModel' => $contentReportModel,
                'page_title' => 'Edit Complaint'
            ]
        );
    }

    public function anyDelete($type, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $contentReportModel = ContentReport::query()->find($id);
        $model = BaseModel::getWallItemModel($type, $contentReportModel->item_id);
        $model->contentReports()->delete();
        if ($model->delete()) {
            Notification::success('Item has been successfully deleted');
        }

        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment() . '/complaints/list/');
    }

    public function anyMakePrivate($type, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $contentReportModel = ContentReport::query()->find($id);
        $model = BaseModel::getWallItemModel($type, $contentReportModel->item_id);
        $model->access_level = Note::ACCESS_PRIVATE;
        if ($model->save()) {
            $contentReportModel->resolved = true;
            $contentReportModel->save();
            Notification::success('Item has been successfully updated');
        }

        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment() . '/complaints/list/');
    }
}
