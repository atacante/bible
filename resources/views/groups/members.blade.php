@if(count($content['members']))
    @foreach($content['members'] as $member)
        <div class="my-item friend-item">
            <div class="my-inner-friends-item">
                @if($member->avatar)
                    <div class="friend-image" style="background: url('{!! $member->avatar!=''?Config::get('app.userAvatars').$member->id.'/thumbs/'.$member->avatar:'' !!}') center no-repeat;"></div>
                @else
                    <div class="friend-image"></div>
                @endif

                <div class="c-friend">
                    <div class="c-friend-text">
                        <span class="friend-name">{!! $member->name !!}{!! Auth::user() && Auth::user()->id == $member->id?' (you)':'' !!}</span>
                        <div>
                            <span style="color:#90949c;">{!! str_limit(strip_tags($member->about_me,'<p></p>'), $limit = 15, $end = '... ') !!}</span>
                        </div>
                    </div>
                </div>

                <div class="btns-group">
                    @if(Auth::user() && Auth::user()->id != $member->id)
                        @if(Auth::user() && Auth::user()->id == $model->owner_id)
                        <a href="{!! url('/groups/ban-member/'.$model->id.'/'.$member->id,[],false) !!}" class="btn1 cu2-btn1 j-ban-member {!! $member->banned?'hidden':'' !!}">Ban</a>
                        <a href="{!! url('/groups/unban-member/'.$model->id.'/'.$member->id,[],false) !!}" class="btn1 cu2-btn1 j-unban-member {!! !$member->banned?'hidden':'' !!}">Unban</a>
                        @endif
                    @endif
                </div>
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