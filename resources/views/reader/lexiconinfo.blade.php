{{--{!! var_dump($lexiconinfo) !!}--}}
<div style="max-width: 700px;">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Symbolism</th>
            <th>Definition</th>
            <th>Strong's</th>
            <th>Transliteration</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{!! $lexiconinfo->symbolism?$lexiconinfo->symbolism:'-' !!}</td>
            <td>{!! $lexiconinfo->strong_1_word_def !!}</td>
            <td>{!! link_to('#',$lexiconinfo->strong_num) !!}</td>
            <td>{!! $lexiconinfo->transliteration !!}</td>
        </tr>
        </tbody>
    </table>
</div>