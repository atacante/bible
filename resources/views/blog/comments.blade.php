{{--@if(Auth::check() || $article->comments->count())--}}
<div class="related-item">
    <div class="item-footer">
        <h3>Comments</h3>
        {{--@role('user')--}}
        <div class="add-comment">
            @include('blog.comments_form')
        </div>
        {{--@endrole--}}
        <div class="item-comments">
            <div class="comments-list">
                @include('blog.comments_list')
            </div>
        </div>
    </div>
</div>
{{--@endif--}}