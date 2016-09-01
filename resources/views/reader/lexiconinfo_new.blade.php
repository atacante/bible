{{--{!! var_dump($lexiconinfo) !!}--}}
    <div class="popup-arrow3"></div>
    <div class="row">
        <div class="col-md-12"><h3><i class="bs-lexicon"></i><b> LEXICON - </b><i>"{!! strtoupper($lexiconinfo->verse_part) !!}"</i></h3></div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-2 medium">STRONG'S</div>
        <div class="col-md-2">{!! link_to('/reader/strongs/'.preg_replace("/[^0-9]/","",$lexiconinfo->strong_num).$lexiconinfo->strong_num_suffix."/".ViewHelper::detectStrongsDictionary($lexiconinfo),$lexiconinfo->strong_num, ['class' => 'clicked']) !!}</div>
        <div class="col-md-4 medium">TRANSLITERATION</div>
        <div class="col-md-4">{!! $lexiconinfo->transliteration !!}</div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-2 medium">SYMBOLISM</div>
        <div class="col-md-10">{!! $lexiconinfo->symbolism?$lexiconinfo->symbolism:'-' !!}</div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-2 medium">DEFINITION</div>
        <div class="col-md-10">{!! $lexiconinfo->definition !!}</div>
    </div>
    <a class="btn-reset j-btn-reset">x</a>