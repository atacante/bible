@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    @include('shop.categories')
                    @include('shop.products')
                </div>

            </div>
        </div>
    </div>
@endsection
