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