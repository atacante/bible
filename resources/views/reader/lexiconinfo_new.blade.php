{{--{!! var_dump($lexiconinfo) !!}--}}
    <div class="popup-arrow3"></div>
    <div class="row">
        <div class="col-md-12 font-size-22"><i class="bs-lexicon"></i> <span class="medium">LEXICON - </span><i>"{!! strtoupper($lexiconinfo->verse_part) !!}"</i></div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">STRONG'S</div>
        <div class="col-md-1 mr2p">{!! link_to('/reader/strongs/'.preg_replace("/[^0-9]/","",$lexiconinfo->strong_num).$lexiconinfo->strong_num_suffix."/".ViewHelper::detectStrongsDictionary($lexiconinfo),$lexiconinfo->strong_num, ['class' => 'clicked']) !!}</div>
        <div class="col-md-2 medium">TRANSLITERATION</div>
        <div class="col-md-7">{!! $lexiconinfo->transliteration !!}</div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">SYMBOLISM</div>
        <div class="col-md-10">{!! $lexiconinfo->symbolism?$lexiconinfo->symbolism:'-' !!}</div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">DEFINITION</div>
        <div class="col-md-10">{!! $lexiconinfo->definition !!}</div>
    </div>
    <a class="btn-reset j-btn-reset">x</a>