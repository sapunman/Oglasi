@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-4">
            @include('home.partials.sidebar')
        </div>
        <div class="col-8">
            <h2>All Messages</h2>
            <ul class="list-group">
                @foreach ($messages as $message)
                    <li class="list-group-item">
                        <p>Oglas: {{$message->ad->title}} <span class="float-end">{{$message->created_at->format('d M Y')}}</span></p>
                        <p>From: {{$message->sender->name}}</p>
                        <p>{{$message->text}}</p>
                        <a href="{{route('home.replay', ['sender_id'=>$message->sender->id, 'ad_id'=>$message->ad_id])}}" class="text-decoration-none">Reply</a>
                    </li>                    
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
