@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center pt-4">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title"><div class="d-flex align-items-center">
                        <h1>{{$question->title}}</h1>
                        <div class="ml-auto"><a href="{{ route('questions.index') }}" class="btn btn-outline-secondary"> <strong>👈</strong> All Questions</a></div>
                    </div>
                    
                    <hr>
                    
                    <div class="media">
                        <div class="d-flex flex-column vote-controls">
                            <a href="#" class="vote-up" title="This question is useful">
                                <i class="fa fa-caret-up fa-3x"></i>
                            </a>
                            <span class="votes-count">1238</span>
                            <a href="#" class="vote-down off" title="This question is not useful">
                                <i class="fa fa-caret-down fa-3x"></i>
                            </a>
                            <a href="" 
                            onclick="event.preventDefault(); document.getElementById('favorite-question-{{$question->id }}').submit(); "
                            class="favorite {{Auth::guest() ? 'off' : ($question->is_favorited ? 'favorited' : '') }}" title="Click to mark as favorite question(Click again to undo)">

                                <i class="fa fa-star"></i>
                                <span class="favorites-count">{{$question->favorites_count}}</span>
                            </a>
                            <form action="/questions/{{$question->id}}/favorites " 
                                id="favorite-question-{{$question->id }}"
                                method="POST" style="display: none;" >
                                @csrf
                                @if ($question->is_favorited)
                                    @method('DELETE')
                                @endif
                            </form>
                        </div>
                        <div class="media-body">
                            {!! $question->body_html !!}
                            <div class="float-right">
                                <span class="text-muted">Answered {{ $question->created_date }} </span>
                                <div class="media">
                                    <a href="{{$question->user->url}}" class="pr-2">
                                        <img src="{{ $question->user->avatar }}" alt="">
                                    </a>
                                    <div class="media-body mt-2">
                                        <a href="{{$question->user->url}}">{{$question->user->name}} </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </div>
    @include('answers._show', [
        'answers' => $question->answers,
        'answersCount' => $question->answers_count,
    ])
    @include('answers._create', [

    ])
</div>
@endsection