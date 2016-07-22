@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('articles'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            {!! Html::link('/admin/articles/create','Create Article', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>User</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Text</th>
                            <th>Published</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['articles']))
                            @foreach($content['articles'] as $article)
                                <tr>
                                    <td>{!! $article->user->name !!}</td>
                                    <td>{!! $article->category->title !!}</td>
                                    <td>{!! $article->title !!}</td>
                                    <td>{!! str_limit($article->text, $limit = 800, $end = '... ')!!}</td>
                                    <td class="text-center">{!! $article->published_at->format($article::DFORMAT) !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a title="Edit article" href="{!! url('/admin/articles/update/'.$article->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                        <a title="Delete article" href="{!! url('/admin/articles/delete',$article->id) !!}" data-toggle="modal"
                                           data-target="#confirm-delete" data-header="Delete Confirmation"
                                           data-confirm="Are you sure you want to delete this item?"><i
                                                    class="fa fa-trash"
                                                    style="color: #367fa9; font-size: 1.4em;"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    <p class="text-center">No any results found</p>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <div class="text-center">
                {!! $content['articles']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection