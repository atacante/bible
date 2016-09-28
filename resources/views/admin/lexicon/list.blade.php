@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('lexicons'))

@section('content')
    @foreach($content['lexicons'] as $code => $lexicon)
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td>
                                    <h4>{!! Html::link('admin/lexicon/view/'.$lexicon['lexicon_code'], $lexicon['lexicon_name'], ['class' => '','style' => ''], false) !!}</h4>
                                </td>
                                <td class="text-center" style="width: 50px;">
                                    <a href="{!! url('admin/lexicon/view/'.$lexicon['lexicon_code'], $lexicon['lexicon_name']) !!}" style="display: block; margin: 10px;"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em;"></i></a>
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