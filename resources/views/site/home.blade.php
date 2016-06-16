@extends('layouts.app')

@section('content')
    <div class="site-index">

        <div class="jumbotron">
            <h3>VERSE OF THE DAY</h3>

            <p class="lead">
                Don’t be misled - you cannot mock the justice of God. You will always harvest what you plant. Those who live only to satisfy their own sinful nature will harvest decay and death from that sinful nature. But those who live to please the Spirit will harvest everlasting life from the Spirit.
            </p>
            <p>Galatians 6:7-8</p>
            <p><a class="btn btn-lg btn-success" href="#">Read More</a></p>
        </div>

        <div class="body-content">

            <div class="row">
                <div class="col-xs-4">
                    <h2>Reader</h2>

                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                        ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                        fugiat nulla pariatur.</p>

                    <p><a class="btn btn-default" href="{{ url('/reader/overview') }}">Open Reader &raquo;</a></p>
                </div>
                <div class="col-xs-4">
                    <h2>Community</h2>

                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                        ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                        fugiat nulla pariatur.</p>

                    <p><a class="btn btn-default" href="{{ url('/auth/register') }}">Register Now &raquo;</a></p>
                </div>
                <div class="col-xs-4">
                    <h2>Events</h2>

                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                        ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                        fugiat nulla pariatur.</p>

                    <p><a class="btn btn-default" href="#">See Events &raquo;</a></p>
                </div>
            </div>

        </div>
    </div>
@endsection
