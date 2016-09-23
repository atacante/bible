@extends('layouts.app')

@include('partials.cms-meta')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h2 class="h2-new mb3">
                {!! $page->title !!}
            </h2>
        </div>
    </div>
    <div class="c-white-content mb2" style="min-height: 577px">
        <div class="inner-pad1">
            <div class="p-medium">
                {{--<img class="pull-right" src="/images/tablet.jpg" style="height: 400px; margin-left: 15px; margin-bottom: 15px;" />--}}
                {!! $page->text !!}
               {{-- <h3>Relevancy</h3>
                <p>In generations past, the access people had to the Bible was very limited. Today, this is no longer the case. However, many people who have access to the Bible think its message does not apply to their lives. At the same time, there are others who believe there is a connection between the Bible and their every day experiences.</p>
                <h3>Information Revolution</h3>
                <p>Over the last decade, the Internet has brought about a revolution which empowers people like never before. With the ability to share, contribute, create, broadcast and communicate, it's easy to express who we are and what we believe with the rest of the world.</p>
                <h3>BibleProject</h3>
                <p>Since its start in 1996, Life.Church's purpose has been to lead people to become fully devoted followers of Christ. In doing so, we have looked for new ways to help people connect the Bible to their daily lives. Our methods have changed over the years as we've incorporated various technologies and strategies. But at the core, our focus remains on relevancy as we consistently strive to demonstrate and teach people how God's Word relates to everyone, no matter where they are in life.</p>
                <p>BibleProject represents a new frontier in Life.Church's efforts. We aren't just building a tool to impact the world using innovative technology, more importantly, we are engaging people into relationships with God as they discover the relevance the Bible has for their lives.</p>--}}
            </div>
        </div>
    </div>
@endsection
