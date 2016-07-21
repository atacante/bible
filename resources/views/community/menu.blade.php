<ul class="nav nav-pills nav-stacked nav-left community-menu" role="menu">
    <li class="{{ (Request::is('community/wall') || Request::is('community') ? 'active' : '') }}" role="presentation">
        <a href="{{ url('community/wall') }}"><i class="fa fa-btn fa-tasks"></i>Public Wall</a>
    </li>
    <li role="separator" class="divider" style=""></li>
    <li class="{{ (Request::is('groups') ? 'active' : '') }}" role="presentation">
        <a href="{{ url('groups') }}"><i class="fa fa-btn fa-users"></i>Groups</a>
    </li>
    @role('user')
    <li title="{!! Auth::user()->isPremium()?'':'Premium Feature' !!}" class="{{ (Request::is('groups/create') ? 'active' : '') }}" role="presentation">
        <a class="{!! Auth::user()->isPremium()?'':'disabled' !!}" href="{{ url('groups/create') }}"><i class="fa fa-btn fa-plus"></i>Create Group</a>
    </li>
    @endrole
    <li role="separator" class="divider" style=""></li>
    <li class="{{ (Request::is('community/find-friends') ? 'active' : '') }}" role="presentation">
        <a href="{{ url('community/find-friends') }}"><i class="fa-btn ion-person-stalker" style="font-size: 16px;"></i>Find Friends</a>
    </li>
    <li class="{{ (Request::is('blog') ? 'active' : '') }}" role="presentation">
        <a href="{{ url('blog') }}"><i class="fa fa-btn fa-newspaper-o"></i>Blog</a>
    </li>
</ul>