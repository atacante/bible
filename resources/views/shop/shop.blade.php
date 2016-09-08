@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="pull-right friends-nav">

                    @include('shop.search')
                </div>

                @include('shop.categories')
                @include('shop.products')
            </div>
        </div>
    </div>
@endsection
