{!! Form::model($itemModel, ['method' => 'put', 'id' => 'complaint-form', 'class' => 'panel','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has($contentReportModel->type().'_text') ? ' has-error' : '' }}">
        {!! Form::label($contentReportModel->type().'_text', 'Description:') !!}
        {!! Form::textarea($contentReportModel->type().'_text',null,['id' => 'location-desc']) !!}
        @if ($errors->has($contentReportModel->type().'_text'))
            <span class="help-block">
                {{ $errors->first($contentReportModel->type().'_text') }}
            </span>
        @endif
    </div>

    {{--<div class="form-group j-access-level {{ $errors->has('access_level') ? ' has-error' : '' }}">
        {!! Form::label('access_level', "Accessibility:") !!}
        <div class="radio">
            <label>
                {!! Form::radio('access_level', App\Note::ACCESS_PRIVATE, true) !!}
                <i class="fa fa-lock" aria-hidden="true"></i>
                Private
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('access_level', App\Note::ACCESS_PUBLIC_ALL, false) !!}
                <i class="fa fa-globe" aria-hidden="true"></i>
                Public - share with everyone
            </label>
        </div>
        @if ($errors->has('access_level'))
            <span class="help-block">
                    {{ $errors->first('access_level') }}
                </span>
        @endif
    </div>--}}
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/complaints/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}