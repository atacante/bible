{!! Form::model($status, ['method' => 'post','url' => '/wall-posts/post-status', 'id' => 'status-form', 'class' => '','role' => 'form','files' => true]) !!}
<div class="c-wall-post">
    <div class="item-body">
        {!! Form::hidden('wall_type',$wallType) !!}
        @if(isset($groupId))
        {!! Form::hidden('rel_id',$groupId) !!}
        @endif
        <div class="c-area-group1">
            {{--<div class="user-image"></div>--}}
            @if(Auth::user()->avatar)
                <div class="user-image" style="background: url('{!! Auth::user()->avatar!=''?Config::get('app.userAvatars').Auth::user()->id.'/thumbs/'.Auth::user()->avatar:'' !!}') center no-repeat;"></div>
            @else
                <div class="user-image"></div>
            @endif
            <div class="form-group {{ $errors->has('status_text') ? ' has-error' : '' }}">
                {!! Form::textarea('status_text',null,['id' => 'status-text', 'class' => 'wall-text-area','placeholder' => "What's on your mind?",'rows' => 1]) !!}
                @if ($errors->has('status_text'))
                    <span class="help-block">
                        {{ $errors->first('status_text') }}
                    </span>
                @endif
            </div>
            <div class="c-privacy">
                <a class="btn-should-see j-btn-should-see" href="#">
                    <i class="bs-s-public cu-print j-status-icon"></i>
                    <span class="c-arrow-sel">
                        <b></b>
                    </span>
                </a>
            </div>
            {!! Form::button('Post', ['type'=>'submit','class'=>'btn4 cu1-btn4']) !!}
        </div>

    </div>
</div>

<div class="box-footer" style="display: none">
    {!! Form::select('access_level',
        [
            App\WallPost::ACCESS_PUBLIC_ALL => 'Public',
            App\WallPost::ACCESS_PUBLIC_FRIENDS => 'Only friends',
            App\WallPost::ACCESS_PRIVATE => 'Only me',
        ],
        Request::input('access_level',App\WallPost::ACCESS_PUBLIC_ALL),['class' => 'j-access-level form-control pull-right', 'style' => 'width: 125px;']) !!}
    <div class="pull-right" style="padding: 6px 10px;">Privacy</div>
</div>
{!! Form::close() !!}

<div class="pop-should-see j-popup-should-see" style="display: none">
    <div class="popup-arrow"></div>
        <div class="pp-title">
            WHO SHOULD SEE THIS?
        </div>
        <ul class="pp-c-items items-status j-status-list">
            {{--<li><a  data-val="all" href="#">All Versions</a></li>--}}
            <li><a class="" data-val="{{App\WallPost::ACCESS_PUBLIC_ALL}}" href="#"><i class="bs-s-public"></i>Public<i class="bs-checkmark right-position"></i></a></li>
            <li><a class="" data-val="{{App\WallPost::ACCESS_PUBLIC_FRIENDS}}" href="#"><i class="bs-friends"></i>Only Friends<i class="bs-checkmark right-position hidden"></i></a></li>
            <li><a class="" data-val="{{App\WallPost::ACCESS_PRIVATE}}" href="#"><i class="bs-s-onlyme"></i>Only Me<i class="bs-checkmark right-position hidden"></i></a></li>
        </ul>
</div>
