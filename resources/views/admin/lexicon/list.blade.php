@extends('admin.layouts.layout')

@section('content')
    @foreach($content['lexicons'] as $code => $lexicon)
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-header  ">
                    <h3 class="box-title">{!! Html::link('admin/lexicon/view/'.$lexicon['lexicon_code'], $lexicon['lexicon_name'], ['class' => '','style' => ''], false) !!}</h3>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection