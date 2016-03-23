@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('version',$versionCode,$versionName))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>

                    <div class="box-tools">
                        @include('admin.partials.filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th style="width: 150px">Verse Number</th>
                            <th>Verse Text</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['verses']))
                            @foreach($content['verses'] as $verses)
                                <tr>
                                    <td>{!! $verses->booksListEn->book_name.' '.$verses->chapter_num.':'.$verses->verse_num !!}</td>
                                    <td>{!! $verses->verse_text !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a href="{!! url('/admin/bible/update/'.$versionCode.'/'.$verses->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em;"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">
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
                {!! $content['verses']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection