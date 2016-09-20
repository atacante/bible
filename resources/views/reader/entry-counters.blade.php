{{--<div class="row">
    <div class="col-lg-4 col-xs-6">
        <div class="mini-box" style="background-color: #f0ad4e">
            <div class="inner">
                <div class="count">{!! count($content['notes']) !!}</div>
                <div class="count-text">Note{!! count($content['notes']) != 1?'s':'' !!}</div>
            </div>
            <div class="icon">
                <i class="fa fa-btn fa-sticky-note"></i>
            </div>
            <a href="{{ url('/notes/create') }}" class="mini-box-footer j-create-note"> Create new note <i class="fa fa-arrow-circle-right"></i> </a>
        </div>
    </div>
    <div class="col-lg-4 col-xs-6">
        <div class="mini-box" style="background-color: #449d44">
            <div class="inner">
                <div class="count">{!! count($content['journal']) !!}</div>
                <div class="count-text">Journal Entr{!! count($content['journal']) != 1?'ies':'y' !!}</div>
            </div>
            <div class="icon">
                <i class="fa fa-btn fa-book"></i>
            </div>
            <a href="{{ url('/journal/create') }}" class="mini-box-footer j-create-journal"> Create new Journal Entry <i class="fa fa-arrow-circle-right"></i> </a>
        </div>
    </div>
    <div class="col-lg-4 col-xs-6">
        <div class="mini-box" style="background-color: #337ab7">
            <div class="inner">
                <div class="count">{!! count($content['prayers']) !!}</div>
                <div class="count-text">Prayer{!! count($content['prayers']) != 1?'s':'' !!}</div>
            </div>
            <div class="icon">
                <i class="fa fa-btn fa-hand-paper-o"></i>
            </div>
            <a href="{{ url('/prayers/create') }}" class="mini-box-footer j-create-prayer"> Create new Prayer <i class="fa fa-arrow-circle-right"></i> </a>
        </div>
    </div>
</div>--}}
<ul class="study-second-panel">
    <li>
        <a href="{{ url('/notes/create') }}" class="j-create-note a-create-journey color7">
            <div class="acj-counter">
                <i class="bs-note"></i> {!! count($content['notes']) !!}
            </div>
            <div class="acj-title">Note{!! count($content['notes']) != 1?'s':'' !!}</div>
        </a>
    </li>
    <li>
        <a href="{{ url('/journal/create') }}" class="j-create-journal a-create-journey color6">
            <div class="acj-counter">
                <i class="bs-journal"></i> {!! count($content['journal']) !!}
            </div>
            <div class="acj-title">Journal Entr{!! count($content['journal']) != 1?'ies':'y' !!}</div>
        </a>
    </li>
    <li>
        <a href="{{ url('/prayers/create') }}" class="j-create-prayer a-create-journey color5">
            <div class="acj-counter">
                <i class="bs-pray"></i> {!! count($content['prayers']) !!}
            </div>
            <div class="acj-title">Prayer{!! count($content['prayers']) != 1?'s':'' !!}</div>
        </a>
    </li>
</ul>
