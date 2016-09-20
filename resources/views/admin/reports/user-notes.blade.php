@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('viewReport'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.reports.view-report-filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success ">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Note text</th>
                            <th class="text-center">Created at</th>
                        </tr>
                        @if(count($content['userNotes']))
                            @foreach($content['userNotes'] as $note)
                                <tr>
                                    <td>
                                        <div class="note-text j-note-text"
                                             data-noteid="{!! $note->id !!}">{!! str_limit(strip_tags($note->note_text,'<p></p>'), $limit = 300, $end = '...') !!}</div>
                                    </td>
                                    <td class="text-center">{!! $note->created_at->format($note::DFORMAT) !!}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    <p class="text-center">No results found</p>
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
                {!! $content['userNotes']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection
