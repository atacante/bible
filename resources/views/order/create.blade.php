    @extends('layouts.app')

    @section('content')
        <div class="c-white-content">
            {!! Form::model($model, ['method' => 'post', 'url' => '/order/checkout']) !!}
            <div class="panel-heading">{{ $page_title or "Page Title" }}</div>
            <div class="panel-body">

                {!! Form::hidden('user_id', $user_id) !!}
                <div class="box-body">
                    @foreach($model->getFillable() as $key => $property)
                        @if($property != 'user_id')
                            @if($key == 1 || $key == 10)
                                <div class="col-md-6">
                            @endif
                                    <div class="form-group {{ $errors->has($property) ? ' has-error' : '' }}">
                                        {!!  Form::label($property, ucwords(str_replace('_',' ', $property))) !!}
                                        {!!  Form::text($property, $model->$property, ['class' => 'form-control input1']) !!}
                                        @if ($errors->has($property))
                                            <span class="help-block">
                                                {{ $errors->first($property) }}
                                            </span>
                                        @endif
                                    </div>
                            @if($key == 9 || $key == 18)
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="mb1 mt13">
            {!! Form::button('Confirm', ['type'=>'submit','class'=>'btn2-kit pull-right']) !!}
            <div class="clearfix"></div>
        </div>
        {!! Form::close() !!}
    @endsection