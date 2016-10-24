@if(Request::input('compare',false))
    <div class="popup-arrow3"></div>
    <div class="row">
        <div class="col-md-12 font-size-22" style="padding-right: 46px">
            <i class="bs-lexicon"></i> <span class="medium">LEXICON - </span>
            <br>
            <i>"{!! strtoupper($lexiconinfo->verse_part) !!}"</i>
        </div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-12 medium">STRONG'S</div>
        <div class="col-md-12">{!! link_to('/reader/strongs/'.preg_replace("/[^0-9]/","",$lexiconinfo->strong_num).$lexiconinfo->strong_num_suffix."/".ViewHelper::detectStrongsDictionary($lexiconinfo),$lexiconinfo->strong_num, ['class' => 'clicked']) !!}</div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-12 medium">TRANSLITERATION</div>
        <div class="col-md-12">{!! $lexiconinfo->transliteration !!}</div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-12 medium">ANALYSIS</div>
        <div class="col-md-12">{!! $lexiconinfo->symbolism?$lexiconinfo->symbolism:'-' !!}</div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-12  medium">DEFINITION</div>
        <div class="col-md-12">{!! $lexiconinfo->definition !!}</div>
    </div>
    <a class="btn-reset j-btn-reset"><i class="bs-close cu1-close"></i></a>
@else
    <div class="popup-arrow3"></div>
    <div class="row">
        <div class="col-md-12 font-size-22" style="padding-right: 46px"><i class="bs-lexicon"></i>
            <span class="medium">LEXICON - </span>
            <i>"{!! strtoupper($lexiconinfo->verse_part) !!}"</i>
        </div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">STRONG'S</div>
        <div class="col-md-1 mr2p">{!! link_to('/reader/strongs/'.preg_replace("/[^0-9]/","",$lexiconinfo->strong_num).$lexiconinfo->strong_num_suffix."/".ViewHelper::detectStrongsDictionary($lexiconinfo),$lexiconinfo->strong_num, ['class' => 'clicked']) !!}</div>
        <span class="resp-line"></span>
        <div class="col-md-2 medium">TRANSLITERATION</div>
        <div class="col-md-7">{!! $lexiconinfo->transliteration !!}</div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">ANALYSIS</div>
        <div class="col-md-10">{!! $lexiconinfo->symbolism?$lexiconinfo->symbolism:'-' !!}</div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">DEFINITION</div>
        <div class="col-md-10">{!! $lexiconinfo->definition !!}</div>
    </div>
    <a class="btn-reset j-btn-reset"><i class="bs-close cu1-close"></i></a>
@endif