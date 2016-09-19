@if(count($content[isset($dataKey)?$dataKey:'groups']['items']))
    @foreach($content[isset($dataKey)?$dataKey:'groups']['items'] as $group)
        <div class="col-md-{!! Request::get('type') == 'my'?'12':'6' !!} clearfix group-item" style="padding: 10px;">
            <div class="pull-left" style="margin-right: 10px;">
                @if($group->group_image)
                    <img class="img-thumbnail" height="100" width="100" data-dz-thumbnail="" alt="" src="{!! Config::get('app.groupImages').$group->id.'/thumbs/'.$group->group_image !!}"/>
                @else
                    <div class="no-avatar img-thumbnail">
                        <div class="no-avatar-text text-center"><i class="fa fa-users fa-4x"></i>
                        </div>
                    </div>
                @endif
            </div>
            <div class="pull-left" style="margin-right: 10px; width: 230px;">
                <div><strong> <a href="{!! url('/groups/view/'.$group->id,[],false) !!}" class="">{!! $group->group_name !!}</a></strong></div>
                <div style="line-height: 12px; color: #90949c; font-size: 12px; margin-bottom: 5px; "><strong>{!! $group->members->count()+1 !!} member{!! $group->members->count()+1 == 1?'':'s' !!}</strong></div>
                <div style="line-height: 16px; font-size: 12px;">
                    <span style="color:#90949c;">{!! str_limit(strip_tags($group->group_desc,'<p></p>'), $limit = 100, $end = '... ') !!}</span>
                </div>
            </div>
            <div title="{!! Auth::user() && Auth::user()->isPremium() || in_array($group->id,$content['joinedGroupsKeys'])?'':'Premium Feature' !!} {!! Auth::user() && Auth::user()->isBanned('group',$group->id)?'You were banned from being part of this group':'' !!}" class="pull-right" style="padding: 0px 0;">
                @if((isset($dataKey) && $dataKey == 'myGroups') || /*in_array($group->id,$content['joinedGroupsKeys']) ||*/ (Auth::user() && $group->owner_id == Auth::user()->id))
                    <a href="{!! url('/groups/update/'.$group->id,[],false) !!}" class=""><i class="fa fa-btn fa-cog" style="font-size: 18px;"></i></a>
                    <a title="Delete group" href="{!! url('/groups/delete/'.$group->id,[],false) !!}" class="j-remove-group" data-toggle="modal"
                       data-target="#confirm-delete" data-header="Delete Confirmation"
                       data-confirm="Are you sure you want to delete this item?"><i class="fa fa-btn fa-trash" style="font-size: 18px;"></i></a>
                @else
                    @if(isset($dataKey) && $dataKey == 'groupsRequested')
                        <a href="{!! url('/groups/accept-request/'.$group->id.'/'.Auth::user()->id,[],false) !!}" class="btn btn-success j-accept-request">Accept</a>
                        <a href="{!! url('/groups/cancel-request/'.$group->id.'/'.Auth::user()->id,[],false) !!}" class="btn btn-danger j-cancel-request " style="display: block; margin-top: 5px;" data-toggle="modal"
                           data-target="#cancel-request-sm" data-header="Reject Request"
                           data-confirm="Are you sure you want to reject this request?">Reject</a>
                    @else
                        <a href="{!! url('/groups/cancel-request/'.$group->id.'/'.Auth::user()->id,[],false) !!}" class="btn btn-danger j-cancel-request {{((!isset($dataKey) || (isset($dataKey) && ($dataKey == 'groups' || $dataKey == 'myGroupsRequests'))) && Auth::check() && in_array($group->id,Auth::user()->myGroupsRequests->modelKeys()))?'':'hidden'}}" style="display: block; margin-top: 5px;" data-toggle="modal"
                           data-target="#cancel-request-sm" data-header="Cancel Request"
                           data-confirm="Are you sure you want to cancel this request?">Cancel Request</a>
                        <a href="{!! url('/groups/leave-group/'.$group->id,[],false) !!}" class="btn btn-danger j-leave-group {!! in_array($group->id,$content['joinedGroupsKeys'])?'':'hidden' !!}"><i class="fa fa-btn fa-minus" style="font-size: 14px;"></i>Leave</a>
                        <a href="{!! url('/groups/join-group/'.$group->id,[],false) !!}" class="btn btn-primary j-join-group {!! in_array($group->id,$content['joinedGroupsKeys']) || in_array($group->id,Auth::user()->myGroupsRequests->modelKeys())?'hidden':'' !!} {!! Auth::user() && Auth::user()->isPremium()?'':'disabled' !!} {!! Auth::user() && Auth::user()->isBanned('group',$group->id)?'disabled':'' !!}"><i class="fa fa-btn fa-plus" style="font-size: 14px;"></i>Join</a>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
    <div class="clearfix"></div>
    @if($content[isset($dataKey)?$dataKey:'groups']['nextPage'])
    <div class="g-footer text-center load-more-block">
        {!! Html::link('/groups/'.ViewHelper::getGroupAction(isset($dataKey)?$dataKey:'groups').'?'.http_build_query(
                array_merge(Request::all(),['page' => $content[isset($dataKey)?$dataKey:'groups']['nextPage']])
            ),'Load More', ['class'=>'btn load-more','style' => '']) !!}
    </div>
    @endif
@else
    <div class="text-center" style="padding: 10px; line-height: 23px;">
        @if(isset($dataKey) && $dataKey == 'joinedGroups')
        You haven’t joined any groups yet.</br>
        Click at the button below to discover.</br>
        <a href="{!! url('/groups?type=all',[],false) !!}" class="btn btn-primary" style="margin: 10px;"><i class="fa fa-btn fa-search"></i>Discover Groups</a>
{{--        <a href="{!! url('/groups?type=all',[],false) !!}">Discover</a> full list to find and join groups.--}}
        @elseif(isset($dataKey) && $dataKey == 'myGroups')
        You haven’t got any groups yet.</br>
        Click at the button below to create new.</br>
        <a href="{!! url('/groups/create',[],false) !!}" class="btn btn-success" style="margin: 10px;"><i class="fa fa-btn fa-plus"></i>Create Group</a>
        @else
        No any results found
        @endif
    </div>
@endif
