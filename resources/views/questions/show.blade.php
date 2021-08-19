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
                            <a href="" class="favorite favorited" title="Click to ark as favorite question(Click again to undo)">
                                <i class="fa fa-star"></i>
                                <span class="favorites-count">123</span>
                            </a>
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
    <div class="row justify-content-center pt-4">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>
                            {{ $question->answers_count. " " . Str::plural('Answer', $question->answers_count) }}
                        </h2>
                    </div>
                    <hr>
                    @foreach ($question->answers as $answer)
                        <div class="media">
                            <div class="d-flex flex-column vote-controls">
                                <a href="#" class="vote-up" title="This answer is useful">
                                    <i class="fa fa-caret-up fa-3x"></i>
                                </a>
                                <span class="votes-count">1238</span>
                                <a href="#" class="vote-down off" title="This answer is not useful">
                                    <i class="fa fa-caret-down fa-3x"></i>
                                </a>
                                <a href="" class="vote-accepted" title="Mark as best answer">
                                    <i class="fa fa-check fa-2x"></i>
                                    {{-- <span class="favorites-count">123</span> --}}
                                </a>
                            </div>

                            <div class="media-body">
                                {!! $answer->body_html !!}
                                <div class="float-right">
                                    <span class="text-muted">Answered {{ $answer->created_date }} </span>
                                    <div class="media">
                                        <a href="{{$answer->user->url}}" class="pr-2">
                                            <img src="{{ $answer->user->avatar }}" alt="">
                                        </a>
                                        <div class="media-body mt-2">
                                            <a href="{{$answer->user->url}}">{{$answer->user->name}} </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr style="background-color: black">
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection