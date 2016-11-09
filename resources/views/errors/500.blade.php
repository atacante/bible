@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h2 class="h2-new mb3">
                Oops! Something went wrong...
            </h2>
        </div>
    </div>
    <div class="c-white-content mb2" style="min-height: 577px">
        <div class="inner-pad1">
            <div class="p-medium">
                Dear Beta Tester</br>
                Looks like something went wrong here.</br>
                Please help us to fix this by submitting a ticket using the support button to the left and let us know what happened right before you ended up here.</br>
                This will help us to refine the process a bit.</br>
                Thank you for your feedback!</br>
            </div>
            {!!  Html::link('/site/contact','Support', ['class'=>'btn2-kit mt16 pull-left cu-btn-pad1']) !!}
        </div>
    </div>
@endsection
