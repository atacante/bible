@if(count($content['members']))
    @foreach($content['members'] as $member)
        <div class="clearfix col-md-6 friend-item" style="margin-bottom: 15px;">
            <div class="pull-left" style="margin-right: 10px;">
                @if($member->avatar)
                    <img class="img-thumbnail" height="100" width="100" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$member->id.'/thumbs/'.$member->avatar !!}"/>
                @else
                    <div class="no-avatar img-thumbnail">
                        <div class="no-avatar-text text-center"><i class="fa fa-user fa-4x"></i></div>
                    </div>
                @endif
            </div>
            <div class="pull-left" style="margin-right: 10px; width: 215px;">
                <div><strong>{!! $member->name !!}{!! Auth::user() && Auth::user()->id == $member->id?' (you)':'' !!}</strong></div>
                <div style="line-height: 16px; font-size: 12px;">
                    <span style="color:#90949c;">{!! str_limit(strip_tags($member->about_me,'<p></p>'), $limit = 70, $end = '... ') !!}</span>
                </div>
            </div>
            <div>
                {!! str_limit(strip_tags($member->people_description,'<p></p>'), $limit = 500, $end = '... '.Html::link(url('/peoples/view/'.$member->id,[],false), 'View Details', ['class' => 'btn btn-success','style' => 'padding: 0 5px;'], true)) !!}
            </div>
            <div class="pull-left">
                @if(Auth::user() && Auth::user()->id != $member->id)
                    @if(Auth::user() && Auth::user()->id == $model->owner_id)
                    <a href="{!! url('/groups/ban-member/'.$model->id.'/'.$member->id,[],false) !!}" class="btn btn-danger j-ban-member {!! $member->banned?'hidden':'' !!}" style="padding: 4px 8px;">Ban</a>
                    <a href="{!! url('/groups/unban-member/'.$model->id.'/'.$member->id,[],false) !!}" class="btn btn-success j-unban-member {!! !$member->banned?'hidden':'' !!}" style="padding: 4px 8px;">Unban</a>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
@endif
<div class="clearfix"></div>
@if($content['nextPage'])
    <div class="col-md-12 text-center load-more-block">
        {!! Html::link('/groups/members/'.$model->id.'?'.http_build_query(
                array_merge(Request::all(),['page' => $content['nextPage']])
            ),'See More', ['class'=>'btn btn-default load-more','style' => 'width:100%;']) !!}
    </div>
@endif