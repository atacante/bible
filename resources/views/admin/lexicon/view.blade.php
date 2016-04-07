@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('lexicon',$lexiconCode,$lexiconName))

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
                            <th>KJV Verse</th>
                            <th>Verse Text</th>
                            <th>Strong's</th>
                            <th>Transliteration</th>
                            <th>Definition</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['lexiconinfo']))
                            @foreach($content['lexiconinfo'] as $lexiconinfo)
                                <tr>
                                    <td>{!! $lexiconinfo->booksListEn->book_name.' '.$lexiconinfo->chapter_num.':'.$lexiconinfo->verse_num !!}</td>
                                    <td>{!! $lexiconinfo->verse_part !!}</td>
                                    <td>{!! link_to('#',$lexiconinfo->strong_num) !!}</td>
                                    <td>{!! $lexiconinfo->transliteration !!}</td>
                                    <td>{!! $lexiconinfo->definition !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a href="{!! url('/admin/lexicon/update/'.$lexiconCode.'/'.$lexiconinfo->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em;"></i></a>
                                    </td>
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
                {!! $content['lexiconinfo']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection