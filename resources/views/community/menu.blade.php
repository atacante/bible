<ul class="nav nav-pills nav-stacked nav-left community-menu c-white-content hide-menu j-hide-menu" role="menu">
    <li class="{{ (Request::is('community/wall') || Request::is('community') ? 'active' : '') }}" role="presentation">
        <a href="{{ url('community/wall') }}"><i class="bs-publicwall"></i>Public Wall</a>
        <span class="caret"></span>
    </li>
    <li role="separator" class="divider" style=""></li>
    <li class="{{ (Request::is('groups') ? 'active' : '') }}" role="presentation">
        <a href="{{ url('groups') }}"><i class="bs-s-groups"></i>Groups</a>
        <span class="caret"></span>
    </li>
    @role('user')
    <li title="{!! Auth::user()->isPremium()?'':'Premium Feature' !!}" class="{{ (Request::is('groups/create') ? 'active' : '') }}" role="presentation">
        <a class="{!! Auth::user()->isPremium()?'':'disabled' !!}" href="{{ url('groups/create') }}"><i class="bs-add"></i>Create Group</a>
        <span class="caret"></span>
    </li>
    @endrole
    <li role="separator" class="divider" style=""></li>
    <li class="{{ (Request::is('community/find-friends') ? 'active' : '') }}" role="presentation">
        <a href="{{ url('community/find-friends') }}"><i class="bs-friends"></i>Friends</a>
        <span class="caret"></span>
    </li>
    @role('user')
    <li role="presentation" class="{!! (Request::segment(2) == 'invite-people')?'active':'' !!}">
        <a href="{!! url('/community/invite-people') !!}"><i class="bs-invite"></i>Invite People</a>
        <span class="caret"></span>
    </li>
    @endrole
    <li role="separator" class="divider" style=""></li>
    <li class="{{ (Request::is('blog') ? 'active' : '') }}" role="presentation">
        <a href="{{ url('blog') }}"><i class="bs-blog"></i>Blog</a>
        <span class="caret"></span>
    </li>
    <!-- Button for async new Posts -->
    <li class="active new-posts hidden j-new-posts" role="presentation">
        <a href="{{ url(Request::url()) }}"><i class="bs-publicwall"></i>New Posts ( <span class="font-size-18"></span> )</a>
        <span class="caret"></span>
    </li>
</ul>