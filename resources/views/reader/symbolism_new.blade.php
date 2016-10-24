@if(Request::input('compare',false))
    <div class="popup-arrow3"></div>
    <div class="row">
        <div class="col-md-12 font-size-22">
            <i class="bs-lexicon"></i> <span class="medium">LEXICON - </span></br>
            <i>"{!! strtoupper($lexiconinfo->verse_part) !!}"</i>
        </div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-12 medium">ANALYSIS</div>
        <div class="col-md-12">{!! $lexiconinfo->symbolism != '...'?$lexiconinfo->symbolism:'' !!}</div>
    </div>

    @include('reader.symbolism_locations_people',['lexiconinfo' => $lexiconinfo])

    <a class="btn-reset j-btn-reset">x</a>
@else
    <div class="popup-arrow3"></div>
    <div class="row">
        <div class="col-md-12 font-size-22"><i class="bs-lexicon"></i> <span class="medium">LEXICON - </span><i>"{!! strtoupper($lexiconinfo->verse_part) !!}"</i></div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">ANALYSIS</div>
        <div class="col-md-10">{!! $lexiconinfo->symbolism != '...'?$lexiconinfo->symbolism:'' !!}</div>
    </div>

    @include('reader.symbolism_locations_people',['lexiconinfo' => $lexiconinfo])
    <a class="btn-reset j-btn-reset">x</a>
@endif