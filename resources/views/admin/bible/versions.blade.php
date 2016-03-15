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
                                <td>
                                    <h4>{!! Html::link('admin/bible/verses/'.$version['version_code'], $version['version_name'], ['class' => '','style' => ''], false) !!}</h4>
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