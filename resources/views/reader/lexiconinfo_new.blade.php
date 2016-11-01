@if(Request::input('compare',false))
    <div class="popup-arrow3"></div>
    <div class="row">
        <div class="col-md-12 font-size-22" style="padding-right: 46px">
            <i class="bs-lexicon lex-verse-part-icon"></i> <span class="medium lex-verse-part-first">LEXICON - </span>
            <div class="lex-verse-part">
                <i>"{!! strtoupper($lexiconinfo->verse_part) !!}"</i>
            </div>
        </div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 medium">STRONG'S</div>
                <div class="col-md-6">{!! link_to('/reader/strongs/'.preg_replace("/[^0-9]/","",$lexiconinfo->strong_num).$lexiconinfo->strong_num_suffix."/".ViewHelper::detectStrongsDictionary($lexiconinfo).'?verse='.Request::input('verse'),$lexiconinfo->strong_num, ['class' => 'clicked']) !!}</div>
            </div>
        </div>
        <hr class="mt0 hr-resp"/>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 medium">TRANSLITERATION</div>
                <div class="col-md-6">{!! $lexiconinfo->transliteration !!}</div>
            </div>
        </div>
    </div>

    @if ($lexiconinfo->symbolism)
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-6 medium">ANALYSIS</div>
        <div class="col-md-6">{!! $lexiconinfo->symbolism?$lexiconinfo->symbolism:'-' !!}</div>
    </div>
    @endif
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-6  medium">DEFINITION</div>
        <div class="col-md-6">{!! $lexiconinfo->definition !!}</div>
    </div>
    <a class="btn-reset j-btn-reset"><i class="bs-close cu1-close"></i></a>
@else


    <div class="popup-arrow3"></div>
    <div class="row">
        <div class="col-md-12 font-size-22" style="padding-right: 46px"><i class="bs-lexicon lex-verse-part-icon"></i>
            <span class="medium lex-verse-part-first">LEXICON - </span>
            <div class="lex-verse-part">
                <i>"{!! strtoupper($lexiconinfo->verse_part) !!}"</i>
            </div>
        </div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">STRONG'S</div>
        <div class="col-md-1 mr2p">{!! link_to('/reader/strongs/'.preg_replace("/[^0-9]/","",$lexiconinfo->strong_num).$lexiconinfo->strong_num_suffix."/".ViewHelper::detectStrongsDictionary($lexiconinfo).'?verse='.Request::input('verse'),$lexiconinfo->strong_num, ['class' => 'clicked']) !!}</div>
        <span class="resp-line"></span>
        <div class="col-md-2 medium">TRANSLITERATION</div>
        <div class="col-md-7">{!! $lexiconinfo->transliteration !!}</div>
    </div>
    @if ($lexiconinfo->symbolism)
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">ANALYSIS</div>
        <div class="col-md-10">{!! $lexiconinfo->symbolism?$lexiconinfo->symbolism:'-' !!}</div>
    </div>
    @endif
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">DEFINITION</div>
        <div class="col-md-10">{!! $lexiconinfo->definition !!}</div>
    </div>
    <a class="btn-reset j-btn-reset"><i class="bs-close cu1-close"></i></a>
@endif