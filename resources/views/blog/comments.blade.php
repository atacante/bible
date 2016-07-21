<div class="item-footer">
    @role('user')
    <div class="add-comment">
        @include('blog.comments_form')
    </div>
    @endrole
    <div class="item-comments">
        <div class="comments-list">
            @include('blog.comments_list')
        </div>
    </div>
</div>