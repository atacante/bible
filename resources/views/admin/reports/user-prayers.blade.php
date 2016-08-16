@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('viewReport'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.reports.user-prayers-filters')
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
                            <th>Prayer text</th>
                            <th class="text-center">Answered</th>
                            <th class="text-center">Created at</th>
                        </tr>
                        @if(count($content['userPrayers']))
                            @foreach($content['userPrayers'] as $prayer)
                                <tr>
                                    <td>
                                        <div class="prayer-text j-prayer-text"
                                             data-prayerid="{!! $prayer->id !!}">{!! str_limit(strip_tags($prayer->text(),'<p></p>'), $limit = 300, $end = '...') !!}</div>
                                    </td>
                                    <td class="text-center">
                                        @if($prayer->answered)
                                            <i class="fa fa-check-circle" aria-hidden="true" style="color: #00a65a;"></i>
                                        @else
                                        @endif
                                    </td>
                                    <td class="text-center">{!! $prayer->created_at->format($prayer::DFORMAT) !!}</td>
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
                {!! $content['userPrayers']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection
