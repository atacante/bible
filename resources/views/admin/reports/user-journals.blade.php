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
                            <th>Journal text</th>
                            <th class="text-center">Created at</th>
                        </tr>
                        @if(count($content['userJournals']))
                            @foreach($content['userJournals'] as $journal)
                                <tr>
                                    <td>
                                        <div class="journal-text j-journal-text"
                                             data-journalid="{!! $journal->id !!}">{!! str_limit(strip_tags($journal->text(),'<p></p>'), $limit = 300, $end = '...') !!}</div>
                                    </td>
                                    <td class="text-center">{!! $journal->created_at->format($journal::DFORMAT) !!}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
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
                {!! $content['userJournals']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection
