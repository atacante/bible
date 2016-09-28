@if(count($content[isset($dataKey)?$dataKey:'groups']['items']))
    <div class="row cu1-row">
    @foreach($content[isset($dataKey)?$dataKey:'groups']['items'] as $group)
        <div class="my-item">
            <div class="my-inner-groups-item">
                @if($group->group_image)
                    <div class="group-image" data-dz-thumbnail="" style="background: url('{!! $group->group_image!=''?Config::get('app.groupImages').$group->id.'/thumbs/'.$group->group_image:'' !!}') center no-repeat;"></div>
                @else
                    <div class="group-image"></div>
                @endif

                <div class="c-group">
                    <a href="{!! url('/groups/view/'.$group->id,[],false) !!}" class="friend-name">{!! $group->group_name !!}</a>
                    <div class="group-ind mt15">
                        <i class="bs-friends color8 font-size-13" title="Private" aria-hidden="true"></i>
                        <span class="color8">{!! $group->members->count()+1 !!}</span>
                        <span class="c-group-type">
                            {!!   ViewHelper::getGroupAccessLevelIcon($group->access_level) !!} {{$group->access_level}}
                        </span>
                    </div>
                    <div class="c-group-text mt15">
                         {!! str_limit(strip_tags($group->group_desc,'<p></p>'), $limit = 25, $end = '... ') !!}
                    </div>
                </div>


                <div title="{!! Auth::user() && Auth::user()->isPremium() || in_array($group->id,$content['joinedGroupsKeys'])?'':'Premium Feature' !!} {!! Auth::user() && Auth::user()->isBanned('group',$group->id)?'You were banned from being part of this group':'' !!}" class="btns-group1">
                    @if((isset($dataKey) && $dataKey == 'myGroups') || /*in_array($group->id,$content['joinedGroupsKeys']) ||*/ (Auth::user() && $group->owner_id == Auth::user()->id))
                        <a title="Update group" href="{!! url('/groups/update/'.$group->id,[],false) !!}" class="btn1-icon"><i class="bs-settings cu-btn-ic"></i></a>
                        <a title="Delete group" href="{!! url('/groups/delete/'.$group->id,[],false) !!}" class="btn2-icon j-remove-group" data-toggle="modal" data-target="#confirm-delete" data-header="Delete Confirmation" data-confirm="Are you sure you want to delete this item?"><i class="cu-btn-ic bs-remove"></i></a>
                    @else
                        @if(isset($dataKey) && $dataKey == 'groupsRequested')
                            <a title="Accept group" href="{!! url('/groups/accept-request/'.$group->id.'/'.Auth::user()->id,[],false) !!}" class="btn1-icon j-accept-request"><i class="bs-checkmark cu-btn-ic"></i></a>
                            <a title="Reject group" href="{!! url('/groups/cancel-request/'.$group->id.'/'.Auth::user()->id,[],false) !!}" class="btn2-icon j-cancel-request" data-toggle="modal" data-target="#cancel-request-sm" data-header="Reject Request" data-confirm="Are you sure you want to reject this request?"><i class="cu-btn-ic bs-close"></i></a>
                        @else
                            @if(Auth::check() && Auth::user()->is('user'))
                            <a href="{!! url('/groups/cancel-request/'.$group->id.'/'.Auth::user()->id,[],false) !!}" class="btn2 cu9-btn1 j-cancel-request {{((!isset($dataKey) || (isset($dataKey) && ($dataKey == 'groups' || $dataKey == 'myGroupsRequests'))) && Auth::check() && in_array($group->id,Auth::user()->myGroupsRequests->modelKeys()))?'':'hidden'}}" data-toggle="modal" data-target="#cancel-request-sm" data-header="Cancel Request" data-confirm="Are you sure you want to cancel this request?">Cancel <br> Request</a>
                            <a href="{!! url('/groups/leave-group/'.$group->id,[],false) !!}" class="btn2 cu5-btn1 j-leave-group {!! in_array($group->id,$content['joinedGroupsKeys'])?'':'hidden' !!}">Leave</a>
                            @endif
                            <a href="{!! url('/groups/join-group/'.$group->id,[],false) !!}" class="btn1 cu5-btn1 j-join-group {!! in_array($group->id,$content['joinedGroupsKeys']) || (Auth::check() && in_array($group->id,Auth::user()->myGroupsRequests->modelKeys()))?'hidden':'' !!} {!! Auth::user() && Auth::user()->isPremium()?'':'disabled' !!} {!! Auth::user() && Auth::user()->isBanned('group',$group->id)?'disabled':'' !!}">Join</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    </div>
    <div class="clearfix"></div>

    @if($content[isset($dataKey)?$dataKey:'groups']['nextPage'])
    <div class="g-footer text-center load-more-block">
        {!! Html::link('/groups/'.ViewHelper::getGroupAction(isset($dataKey)?$dataKey:'groups').'?'.http_build_query(
                array_merge(Request::all(),['page' => $content[isset($dataKey)?$dataKey:'groups']['nextPage']])
            ),'Load More', ['class'=>'btn1 load-more','style' => 'width:100%;']) !!}
    </div>
    @endif
@else
    <div class="text-center" style="padding: 10px; line-height: 23px;">
        @if(isset($dataKey) && $dataKey == 'joinedGroups')
        You haven’t joined any groups yet.</br>
        Click at the button below to discover.</br>
        <a href="{!! url('/groups?type=all',[],false) !!}" class="btn2-kit" style="margin: 10px;"><i class="bs-search cu-search2"></i>Discover Groups</a>
        @elseif(isset($dataKey) && $dataKey == 'myGroups')
        You haven’t got any groups yet.</br>
        Click at the button below to create new.</br>
        <a href="{!! url('/groups/create',[],false) !!}" class="btn2-kit {!! Auth::user() && Auth::user()->isPremium()?'':'disabled' !!}" style="margin: 10px;"><i class="bs-add cu-search2"></i>Create Group</a>
        @else
        No results found
        @endif
    </div>
@endif
