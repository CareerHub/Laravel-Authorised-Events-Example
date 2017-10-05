@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Events
                <span class="pull-right">{{count($events->items)}} events found</span>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        @foreach($events->items as $event)
                            <a href="{{url("events/".$event->id)}}" class="list-group-item">
                                <h4 class="list-group-item-heading">{{$event->title}}</h4>
                                <p class="list-group-item-text">{{$event->summary}}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
