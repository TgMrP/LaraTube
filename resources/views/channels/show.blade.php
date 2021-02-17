@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        {{ $channel->name }}

                        <a href="{{ route('channel.upload', $channel->id) }}">Upload Video</a>
                    </div>

                    <div class="card-body">
                        @if($channel->editable())
                        <form id="update-channel-form" action="{{ route('channels.update', $channel->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                        @endif
                            <div class="form-group  row justify-content-center">
                                <div class="channel-avatar">
                                    @if($channel->editable())
                                        <div onclick="document.getElementById('image').click()" class="channel-avatar-overlay">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <input type="file" name="image" id="image" style="display: none;" onchange="document.getElementById('update-channel-form').submit()">
                                    @endif
                                    <img src="{{$channel->image()}}" alt="" />
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <subscribe-button :channel="{{ $channel }}" :initial-subscriptions="{{ $channel->subscriptions }}" />
                            </div>


                            @if($channel->editable())

                                <div class="form-group">
                                    <label for="name" class="form-control-label">
                                        Name
                                    </label>
                                    <input id="name" name="name" value="{{$channel->name}}" type="text" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-control-label">
                                        Description
                                    </label>
                                    <textarea id="description" name="description" cols="3" rows="3" class="form-control">{{$channel->description}}</textarea>
                                </div>

                                @if($errors->any())
                                    <ul class="list-group mb-3">
                                        @foreach($errors->all() as $error)
                                            <li class="text-danger list-group-item">
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                <button type="submit" class="btn btn-info">
                                    {{ __('Update Channel') }}
                                </button>

                            @else

                                <div class="form-group">
                                    <h4 class="text-center">
                                        {{ $channel->name }}
                                    </h4>

                                    <p class="text-center">
                                        {{ $channel->description }}
                                    </p>

                                </div>

                            @endif

                        @if($channel->editable())
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
