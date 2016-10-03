<div class="friend-item user-popup box-body">
    <div class="row">
        <div class="col-md-3">
            <div class="row">
                @if($model->avatar)
                    <div class="friend-image" style="background: url('{!! Config::get('app.userAvatars').$model->id.'/thumbs/'.$model->avatar !!}') center no-repeat;"></div>
                @else
                    <div class="friend-image"></div>
                @endif
            </div>
            <div class="row">
                <a href="{!! url('/user/remove-friend/'.$model->id,[]) !!}" class="btn2 cu-cancel j-remove-friend {!! in_array($model->id,$myFriends)?'':'hidden' !!}"
                   data-toggle="modal"
                   data-target="#cancel-request-sm"
                   data-itemid="{!! $model->id !!}"
                   data-header="Remove user from friends"
                   data-callclass="j-remove-friend"
                   data-confirm="Are you sure you want to remove this user from friends?">Unfriend</a>
                <a href="{!! url('/user/cancel-friend-request/'.$model->id,[]) !!}" class="btn2 cu-cancel j-cancel-friend-request {!! in_array($model->id,$myRequests) && !in_array($model->id,$myFriends)?'':'hidden' !!}"
                   data-toggle="modal"
                   data-target="#cancel-request-sm"
                   data-itemid="{!! $model->id !!}"
                   data-header="Cancel Request"
                   data-callclass="j-cancel-friend-request"
                   data-confirm="Are you sure you want to cancel this request?">Cancel Request</a>
                @if(in_array($model->id,$requests))
                    @if(in_array($model->id,$ignoredRequests))
                        <a href="#" class="btn2 cu-cancel disabled">Ignored</a>
                    @else
                        <a href="{!! url('/user/approve-friend-request/'.$model->id,[]) !!}" class="btn1 cu-cancel j-approve-friend-request {!! !in_array($model->id,$myFriends) && in_array($model->id,$requests)?'':'hidden' !!}">Confirm Request</a>
                    @endif
                @else
                    <a href="{!! url('/user/request-friend/'.$model->id,[]) !!}" class="btn1 cu-cancel j-follow-friend {!! in_array($model->id,$myFriends) || in_array($model->id,$myRequests)?'hidden':'' !!}">Add Friend</a>
                @endif
            </div>
        </div>
        <div class="col-md-9" style="font-size: 14px; color: #535353;">
            <h4><strong>{!! $model->name !!}</strong></h4>
            <div class="about-me">{!! $model->about_me !!}</div>
            @if($model->country_id)
                <div>
                    <strong>Country</strong>
                    <span>{!! $model->country->nicename !!}</span>
                </div>
            @endif
            @if($model->state)
                <div>
                    <strong>State</strong>
                    <span>{!! $model->state !!}</span>
                </div>
            @endif
            @if($model->city)
                <div>
                    <strong>City</strong>
                    <span>{!! $model->city !!}</span>
                </div>
            @endif
            @if($model->church_name)
                <div>
                    <strong>Church</strong>
                    <span>{!! $model->church_name !!}</span>
                </div>
            @endif
            @if(count($relatedGroups))
                <div>
                    <div><strong>Related groups:</strong></div>
                    <div>
                    @foreach($relatedGroups as $key => $group)
                        <a href="{!! url('groups/view',[$group->id]) !!}" target="_blank">{!! $group->group_name !!}</a>{!! $key+1 < count($relatedGroups)?',':'' !!}
                    @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>