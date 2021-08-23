<div class="row justify-content-center pt-4">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>
                            {{ $answersCount. " " . Str::plural('Answer', $answersCount) }}
                        </h2>
                    </div>
                    <hr class="mb-5">
                    @include('layouts._messages')
                    
                    @foreach ($answers as $answer)
                        <div class="media">
                            <div class="d-flex flex-column vote-controls">
                                <a href="#" class="vote-up" title="This answer is useful">
                                    <i class="fa fa-caret-up fa-3x"></i>
                                </a>
                                <span class="votes-count">1238</span>
                                <a href="#" class="vote-down off" title="This answer is not useful">
                                    <i class="fa fa-caret-down fa-3x"></i>
                                </a>
                                
                                @can('accept', $answer)
                                <a href="" class="{{ $answer->status }}"
                                    onclick="event.preventDefault(); document.getElementById('accept-answer-{{$answer->id }}').submit(); "
                                    title="Mark as best answer">
                                    <i class="fa fa-check fa-2x"></i>
                                    {{-- <span class="favorites-count">123</span> --}}
                                </a>
                                <form action="{{route('answers.accept', $answer->id) }} " id="accept-answer-{{$answer->id }}"
                                    method="POST" style="display: none;" >
                                    @csrf
                                </form>
                                
                                @else
                                    @if ($answer->is_best)                           
                                        <a href="" class="{{ $answer->status }}"
                                            title="Question owner accepted it as best answer">
                                            <i class="fa fa-check fa-2x"></i>
                                        </a>
                                    @endif
                                    
                                @endcan

                            </div>

                            <div class="media-body">
                                {!! $answer->body_html !!}
                                <div class="row">
                                    <div class="col-4">
                                        <div class="d-flex">
                                            @can('update', $answer)
                                                <a href="{{ route('questions.answers.edit', [$question->id, $answer->id]) }}" class="btn btn-sm btn-outline-info mr-2">Edit</a>
                                            @endcan

                                            @can('delete', $answer)
                                                <form action="{{route('questions.answers.destroy', [$question->id, $answer->id])}} " method="post" class="form-delete">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Are you sure?') " class="btn btn-sm btn-outline-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        
                                    </div>
                                    <div class="col-4">
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
                        </div>
                        <hr style="background-color: black" class="mb-5 mt-5">
                    @endforeach
                </div>
            </div>
        </div>
    </div>