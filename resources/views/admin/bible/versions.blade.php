@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('bibles'))

@section('content')
    @foreach($content['versions'] as $code => $version)
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td width="400">
                                    <h4>{!! Html::link('admin/bible/verses/'.$version['version_code'], $version['version_name'], ['class' => '','style' => ''], false) !!}</h4>
                                </td>
                                <td>
                                    <div class="checkbox" title="Version will be available in reader">
                                        <label>
                                            {!! Form::hidden('enabled', 0) !!}
                                            {!! Form::checkbox('enabled', $version['enabled'],$version['enabled'],['class' => 'j-version-status','data-version' => $version['version_code']]) !!}
                                            <span>Enabled for Reader</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="checkbox" title="Version will be available for comparison">
                                        <label>
                                            {!! Form::hidden('enabled_to_compare', 0) !!}
                                            {!! Form::checkbox('enabled_to_compare', $version['enabled_to_compare'],$version['enabled_to_compare'],['class' => 'j-version-status','data-version' => $version['version_code']]) !!}
                                            <span>Enabled for compare</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="text-center" style="width: 50px;">
                                    <a href="{!! url('admin/bible/verses', $version['version_code']) !!}" style="display: block; margin: 10px;"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em;"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection