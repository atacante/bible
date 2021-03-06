<div class="row">
    <div class="col-md-12">
        <div class="c-white-content" style="margin-bottom: 15px">
            <div class="inner-pad6">
                <h5 class="h5-kit">Groups You Manage</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 j-group-items">
                @include('groups.items',['dataKey' => 'myGroups'])
            </div>
        </div>
    </div>


    <div class="col-md-12">
        @if($content['groupsRequested']['items']->count())
            <div class="c-white-content m-cu1">
                <div class="inner-pad6">
                    <h5 class="h5-kit">Group Invitations</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 j-group-items">
                    @include('groups.items',['dataKey' => 'groupsRequested'])
                </div>
            </div>
        @endif

        @if($content['myGroupsRequests']['items']->count())
            <div class="c-white-content m-cu1">
                <div class="inner-pad6">
                    <h5 class="h5-kit">Groups You Have Requested to Join</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 j-group-items">
                    @include('groups.items',['dataKey' => 'myGroupsRequests'])
                </div>
            </div>
        @endif

        <div class="c-white-content m-cu1">
            <div class="inner-pad6">
                <h5 class="h5-kit">Groups You Have Joined</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 j-group-items">
                @include('groups.items',['dataKey' => 'joinedGroups'])
            </div>
        </div>
    </div>
</div>