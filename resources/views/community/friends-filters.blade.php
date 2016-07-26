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
    {!! Form::text('search',Request::input('search'),['placeholder' => 'Search by name or email...','class' => 'form-control','style' => 'width:215px; display:inline; padding-right: 30px;']) !!}
    {!! Form::token() !!}
    {!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary']) !!}
    <a href="{!! url('/community/find-friends') !!}" style="position: absolute; top: 2px; right: 66px; font-size: 18px;"><i class="fa fa-btn fa-times"></i></a>
{!! Form::close() !!}