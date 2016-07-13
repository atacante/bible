{!! Form::open(['method' => 'get','url' => '/community/find-friends?']) !!}
    {{--@role('user')
    <div class="form-group">
        <div class="radio">
            <label>
                {!! Form::radio('type', 'all', !Request::get('type') || Request::get('type') == 'all') !!}
                All users
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('type', 'my', Request::get('type') == 'my') !!}
                Only my friends
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('type', 'new', Request::get('type') == 'new') !!}
                Only new users
            </label>
        </div>
    </div>
    @endrole--}}
    {!! Form::hidden('type',Request::input('type')) !!}
    {!! Form::text('search',Request::input('search'),['placeholder' => 'Search by name or email...','class' => 'form-control','style' => 'width:200px; display:inline;']) !!}
    {!! Form::token() !!}
    {!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary']) !!}
{{--        {!! Html::link('/community/find-friends','Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}--}}
{!! Form::close() !!}