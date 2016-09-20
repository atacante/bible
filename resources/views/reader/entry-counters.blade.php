<ul class="study-second-panel">
    <li>
        <div {{--href="{{ url('/notes/create') }}"--}} class="a-create-journey color7">
            <div class="acj-counter">
                <i class="bs-note"></i> {!! count($content['notes']) !!}
            </div>
            <div class="acj-title">Note{!! count($content['notes']) != 1?'s':'' !!}</div>
        </div>
    </li>
    <li>
        <div {{--href="{{ url('/journal/create') }}"--}} class="a-create-journey color6">
            <div class="acj-counter">
                <i class="bs-journal"></i> {!! count($content['journal']) !!}
            </div>
            <div class="acj-title">Journal Entr{!! count($content['journal']) != 1?'ies':'y' !!}</div>
        </div>
    </li>
    <li>
        <div {{--href="{{ url('/prayers/create') }}"--}} class="a-create-journey color5">
            <div class="acj-counter">
                <i class="bs-pray"></i> {!! count($content['prayers']) !!}
            </div>
            <div class="acj-title">Prayer{!! count($content['prayers']) != 1?'s':'' !!}</div>
        </div>
    </li>
</ul>
