@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('viewReport'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.reports.user-views-filters')
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
                            <th>Page</th>
                            <th>Dictionary Type</th>
                            <th class="text-center">Reviews</th>
                            <th class="text-center">First View</th>
                            <th class="text-center">Last View</th>
                        </tr>
                        @if(count($content['userViews']))
                            @foreach($content['userViews'] as $view)
                                <tr>
                                    <td>
                                        <div>
                                            {!! Html::link(url('/reader/strongs',[$view->item->strong_num,$view->item->dictionary_type])
                                                ,"Strong's ".$view->item->strong_num, ['target' => '_blank','class'=>'label label-success','style' => 'margin-bottom:10px;']) !!}
                                        </div>
                                    </td>
                                    <td>
                                        {!! $view->item->dictionary_type !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $view->views !!}
                                    </td>
                                    <td class="text-center">{!! $view->created_at->format($view::DFORMAT) !!}</td>
                                    <td class="text-center">{!! $view->updated_at->format($view::DFORMAT) !!}</td>
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
                {!! $content['userViews']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection
