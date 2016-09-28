<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="j-chapter-content" style="margin: 0 20px;">
    <div class="row" style="position: relative;">
        <h3 class="text-center">{!! $content['heading'] !!}</h3>
    </div>
    <div class="row">
        <div class="j-reader-block row col-md-12"
             style="line-height: 30px; text-align: justify;">
            @foreach($content['verses'] as $verse)
                <span style="word-wrap: normal">
                <b>{!! $verse->verse_num !!}</b>&nbsp;{!! $verse->verse_text !!}
            </span>
            @endforeach
        </div>
    </div>
</div>
</body>
