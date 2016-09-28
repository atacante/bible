{{--<div class="stars-block">--}}
    {{--<div class="star-line">--}}
        {{--<a title="Notes" class="star-link" data-type="notes" href="#notes">--}}
            {{--<div class="star star-n {!! count($content['notes']) > 0?'active':'' !!}">N</div>--}}{{----}}{{-- ViewHelper::getEntriesCount('note',$entry) > 0 --}}
            {{--<span class="fa fa-star {!! count($content['notes']) > 0?'active':'' !!}" aria-hidden="true" style=""></span>--}}
            {{--<span class="star-label" style="">N</span>--}}
        {{--</a>--}}
    {{--</div>--}}
    {{--<div class="star-line">--}}
        {{--<a title="Journal Entries" class="star-link" data-type="journal" href="#journal" style="">--}}
            {{--<span class="fa fa-star {!! count($content['journal']) > 0?'active':'' !!}" aria-hidden="true" style=""></span>--}}
            {{--<span class="star-label" style="">J</span>--}}
            {{--<div class="star star-j {!! count($content['journal']) > 0?'active':'' !!}">J</div>--}}
        {{--</a>--}}
        {{--<a title="Prayers" class="star-link" data-type="prayers" href="#prayers">--}}
{{--            <div class="star star-p {!! count($content['prayers']) > 0?'active':'' !!}">P</div>--}}
            {{--<span class="fa fa-star {!! count($content['prayers']) > 0?'active':'' !!}" aria-hidden="true" style=""></span>--}}
            {{--<span class="star-label" style="">P</span>--}}
        {{--</a>--}}
    {{--</div>--}}
{{--</div>--}}

<div class="stars-block" style="">
    <div class="star-line">
        <a title="Notes" class="star-link notes" data-type="notes" href="#notes" style="">
            {{--<div class="star star-n {!! count($content['notes']) > 0?'active':'' !!}">N</div>--}}{{-- ViewHelper::getEntriesCount('note',$entry) > 0 --}}
            <span class="fa fa-star {!! count($content['notes']) > 0?'active':'' !!}" aria-hidden="true" style=""></span>
            <span class="star-label" style="">N</span>
        </a>
        <a title="Journal Entries" class="star-link journal" data-type="journal" href="#journal" style="">
            <span class="fa fa-star {!! count($content['journal']) > 0?'active':'' !!}" aria-hidden="true" style=""></span>
            <span class="star-label" style="">J</span>
            {{--<div class="star star-j {!! count($content['journal']) > 0?'active':'' !!}">J</div>--}}
        </a>
        <a title="Prayers" class="star-link prayers" data-type="prayers" href="#prayers" style="">
            {{--            <div class="star star-p {!! count($content['prayers']) > 0?'active':'' !!}">P</div>--}}
            <span class="fa fa-star {!! count($content['prayers']) > 0?'active':'' !!}" aria-hidden="true" style=""></span>
            <span class="star-label" style="">P</span>
        </a>
    </div>
</div>
