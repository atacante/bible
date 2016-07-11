<div class="row">
    <div class="col-md-6">
        <div class="group-block">
            <div class="g-header">
                <div class=""><strong>Groups You Manage</strong></div>
            </div>
            <div class="g-body">
                @include('groups.items',['dataKey' => 'myGroups'])
            </div>
        </div>
    </div>
    <div class="col-md-6">
        @if($content['groupsRequested']['items']->count())
        <div class="group-block">
            <div class="g-header">
                <div class=""><strong>Groups You Invited</strong></div>
            </div>
            <div class="g-body">
                @include('groups.items',['dataKey' => 'groupsRequested'])
            </div>
        </div>
        @endif
        <div class="group-block">
            <div class="g-header">
                <div class=""><strong>Groups You Joined</strong></div>
            </div>
            <div class="g-body">
                @include('groups.items',['dataKey' => 'joinedGroups'])
            </div>
        </div>
    </div>
</div>