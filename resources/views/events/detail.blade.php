@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Event Detail</div>
                <div class="panel-body">
                    <p>
                        <?php
                        //Build Event Link
                            $base = config("oauth.baseURL");
                            $uri = config("oauth.baseURI");
                            $link = $base.$uri.'\\students\\events\\detail\\'.$event->id;
                        ?>
                        <a href="{{url('events')}}">Back to Event List</a>
                        <a href="{{$link}}" class="pull-right">View in CareerHub</a>
                    </p>
                    <h3>{{$event->title}}</h3>
                    <p><strong>Summary: </strong> {{$event->summary}}</p>
                    <p><strong>Venue: </strong> {{$event->venue}}</p>
                    <p><strong>Start: </strong> {{\Carbon\Carbon::parse($event->start)->toDayDateTimeString()}}</p>
                    <p><strong>End: </strong> {{\Carbon\Carbon::parse($event->end)->toDayDateTimeString()}}</p>
                    <p><strong>Detail: </strong> {!! $event->details !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
