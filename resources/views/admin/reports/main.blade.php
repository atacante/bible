@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('reports'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.reports.main-filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-bookmarks ion-ios-bookmarks-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">
                        <strong class="pull-right">{!! $content['notesCount'] !!}</strong>
                        <span>Notes</span>
                    </span>
                    <span class="info-box-text">
                        <strong class="pull-right">{!! $content['journalsCount'] !!}</strong>
                        <span>Journals</span>
                    </span>
                    <span class="info-box-text">
                        <strong class="pull-right" title="Total/Answered">{!! $content['prayersCount'] !!}/{!! $content['answeredPrayersCount'] !!}</strong>
                        <span>Prayers</span>
                    </span>
                    <span class="info-box-text">
                        <strong class="pull-right">{!! $content['statusesCount'] !!}</strong>
                        <span>Statuses</span>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">
                        <strong class="pull-right">{!! $content['groupsCount'] !!}</strong>
                        <span>Groups</span>
                    </span>
                    <span class="info-box-text">
                        <strong class="pull-right">{!! $content['groupJoinsCount'] !!}</strong>
                        <span >Group joins</span>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa ion-ios-book-outline"></i></span>

                <div class="info-box-content">
                    <span title="Reader pages views" class="info-box-text">
                        <strong class="pull-right">{!! $content['readerViewsCount'] !!}</strong>
                        <span>Reader</span>
                    </span>
                    <span title="Lexicon pages views" class="info-box-text">
                        <strong class="pull-right">{!! $content['lexiconViewsCount'] !!}</strong>
                        <span>Lexicon</span>
                    </span>
                    <span title="Strong's pages views" class="info-box-text">
                        <strong class="pull-right">{!! $content['strongsViewsCount'] !!}</strong>
                        <span>Strongs</span>
                    </span>
                    <span title="Blog pages views" class="info-box-text">
                        <strong class="pull-right">{!! $content['blogViewsCount'] !!}</strong>
                        <span>Blog</span>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-personadd-outline"></i></span>

                <div class="info-box-content">
                    {{--<span class="info-box-text">Referred users</span>
                    <span class="info-box-number">12</span>--}}
                    <span title="Referred users" class="info-box-text">
                        <strong class="pull-right">{!! $content['referredUsersCount'] !!}</strong>
                        <span>Ref users</span>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success ">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th style="width: 50px">ID</th>
                            <th>Name</th>
                            <th class="text-center"># of notes <br /> created</th>
                            <th class="text-center"># of journals <br /> created</th>
                            <th class="text-center"># of prayers <br /> created</th>
                            <th class="text-center"># of answered <br /> prayers</th>
                            <th class="text-center"># of status <br /> updates</th>
                            <th class="text-center"># of groups <br /> created</th>
                            <th class="text-center"># of groups <br /> joined</th>
                            <th class="text-center"># of page views <br /> in ereader</th>
                            <th class="text-center"># of page views <br /> in lexicon</th>
                            <th class="text-center"># of strongs <br /> page views</th>
                            <th class="text-center"># of blog <br /> page views</th>
                            <th class="text-center"># of referred <br /> users</th>
                        </tr>
                        @if(count($content['users']))
                            @foreach($content['users'] as $user)
                                <tr>
                                    <td>{!! $user->id !!}</td>
                                    <td>{!! $user->name !!}</td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-notes/'.$user->id) !!}">{!! $user->notes_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-journals/'.$user->id) !!}">{!! $user->journal_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-prayers/'.$user->id) !!}">{!! $user->prayers_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-prayers/'.$user->id.'?answered=1') !!}">{!! $user->answered_prayers_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-status-updates/'.$user->id) !!}">{!! $user->statuses_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-groups/'.$user->id.'?type=created') !!}">{!! $user->created_groups_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-groups/'.$user->id.'?type=joined') !!}">{!! $user->joined_groups_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-reader-pages/'.$user->id) !!}">{!! $user->reader_views_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-lexicon-pages/'.$user->id) !!}">{!! $user->lexicon_views_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-strongs-pages/'.$user->id) !!}">{!! $user->strongs_views_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/user-blog-pages/'.$user->id) !!}">{!! $user->blog_views_count !!}</a></td>
                                    <td class="text-center"><a href="{!! url('/admin/reports/referred-users/'.$user->id) !!}">{!! $user->invites_count !!}</a></td>
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
                {!! $content['users']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection