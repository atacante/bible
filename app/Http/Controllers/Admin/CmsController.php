<?php

namespace App\Http\Controllers\Admin;

use App\BlogArticle;
use App\CmsPage;
use App\BlogCategory;
use App\Helpers\ViewHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Krucas\Notification\Facades\Notification;

class CmsController extends Controller
{

    public function getIndex()
    {
        $content['categoriesCount'] = BlogCategory::query()->count();
        $content['articlesCount'] = BlogArticle::query()->count();

        return view(
            'admin.blog.list',
            [
                'page_title' => 'CMS',
                'content' => $content,
                'filterAction' => 'blog/list/',
            ]
        );
    }

    public function getList()
    {

        Session::flash('backUrl', Request::fullUrl());

        /*$cmsModel = BlogArticle::query();*/

        $content['cms'] = CmsPage::where('content_type', '!=', 'home')->orderBy('created_at', SORT_DESC)->paginate(20);

        return view(
            'admin.cms.list',
            [
                'page_title' => 'Static Pages',
                'content' => $content,
                'filterAction' => 'cms/list/',
            ]
        );
    }

    public function getHome()
    {

        Session::flash('backUrl', Request::fullUrl());

        $content['cms'] = CmsPage::where('content_type', '=', 'home')->orderBy('created_at', SORT_DESC)->paginate(20);

        return view(
            'admin.cms.list',
            [
                'page_title' => 'Static Pages',
                'content' => $content,
                'filterAction' => 'cms/list/',
            ]
        );
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = CmsPage::query()->find($id);
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            if ($model->update($data)) {
                $this->anyUploadImage($model);
                Notification::success(($model->content_type == CmsPage::CONTENT_HOME)?'Homepage has been successfully updated':'CMS has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment() .
                    (($model->content_type == CmsPage::CONTENT_HOME)?'/cms/home/' :'/cms/list/'));
        }
        return view(
            'admin.cms.update',
            [
                'model' => $model,
                'page_title' => 'Edit CMS'
            ]
        );
    }

    public function anyUploadImage($model)
    {
        $files = ['file', 'm_file'];

        foreach ($files as $uploaded_file) {
            if (Input::hasFile($uploaded_file)) {
                $file = Input::file($uploaded_file);

                $tmpFilePath = Config::get('app.homeImages');
                $tmpThumbPath = $tmpFilePath . 'thumbs/';
                $tmpFileName = time() . '-' . $file->getClientOriginalName();
                $file = $file->move(public_path() . $tmpFilePath, $tmpFileName);
                $path = $tmpFilePath . $tmpFileName;

                $this->makeDir(public_path() . $tmpThumbPath);
                $thumbPath = public_path($tmpThumbPath . $tmpFileName);

                if ($file) {
                    if ($uploaded_file == 'file') {
                        $this->unlinkLocationImage($model->background);
                        $model->background = $tmpFileName;
                        // Resizing
                        Image::make($file->getRealPath())->fit(200, 100)->save($thumbPath)->destroy();
                    } elseif ($uploaded_file == 'm_file') {
                        $this->unlinkLocationImage($model->background_mobile);
                        $model->background_mobile = $tmpFileName;
                        // Resizing
                        Image::make($file->getRealPath())->fit(100, 100)->save($thumbPath)->destroy();
                    }

                    $model->save();
                }
            }
        }


        return true;
    }

    private function makeDir($path)
    {
        if (!is_dir($path)) {
            return mkdir($path);
        }
        return true;
    }

    private function unlinkLocationImage($filename)
    {
        $tmpFilePath = public_path(Config::get('app.homeImages').$filename);
        $tmpThumbPath = public_path(Config::get('app.homeImages').'thumbs/'.$filename);

        if (is_file($tmpFilePath)) {
            unlink($tmpFilePath);
        }
        if (is_file($tmpThumbPath)) {
            unlink($tmpThumbPath);
        }
    }
}
