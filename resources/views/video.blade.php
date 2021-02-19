@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if ($video->editable())
                    <form method="POST" action="{{ route('videos.update', $video->id) }}">
                        @csrf
                        @method('PUT')
                @endif
                <div class="card-header">
                    {{ $video->title }}
                </div>

                <div class="card-body">
                    <video-js
                        id="video"
                        class="video-js"
                        controls
                        preload="auto"
                        width="640"
                        height="264"
                        poster="{{ $video->thumbnail }}"
                        data-setup="{}"
                    >
                        <source src="{{ asset(Storage::url("videos/{$video->id}/{$video->id}.m3u8")) }}" type="application/x-mpegURL" />
                        <p class="vjs-no-js">
                            To view this video please enable JavaScript, and consider upgrading to a
                            web browser that
                            <a href="https://videojs.com/html5-video-support/" target="_blank">
                                supports HTML5 video
                            </a>
                        </p>
                    </video-js>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="w-75">
                            <h4 class="mt-3 text-break">
                                @if ($video->editable())
                                    <input type="text" class="form-control" value="{{ $video->title }}" name="title" style="border:none;">
                                @else
                                    {{ $video->title }}
                                @endif
                            </h4>
                            {{ $video->views }} {{ Str::plural('view', $video->views)}}
                        </div>

                        <div class="w-25 text-center">
                            <votes :default-votes="{{ $video->votes }}" entity-id="{{ $video->id }}" entity-owner="{{ $video->channel->user_id }}"></votes>
                        </div>
                    </div>

                    <hr>

                    <div>
                        @if ($video->editable())
                        <textarea name="description" cols="3" rows="3" class="form-control">{{ $video->description }}</textarea>
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-info btn-sm">Update video details</button>
                        </div>
                        @else
                            {{ $video->description }}
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <div class="media">
                            @if ($video->channel->image())
                            <img src="{{ $video->channel->image() }}" alt="" class="rounded-circle mr-3" width="50" height="50">
                            @else
                            <avatar username="{{ $video->channel->name }}" :size="50" class="rounded-circle mr-3"></avatar>
                            @endif
                            <div class="media-body ml-2">
                                <h5 class="mt-0 mb-0">
                                    {{ $video->channel->name }}
                                </h5>
                                <span class="small">Published on {{ $video->created_at->toFormattedDateString() }}</span>
                            </div>
                        </div>

                        <subscribe-button :channel="{{ $video->channel }}" :initial-subscriptions="{{ $video->channel->subscriptions }}" />
                    </div>
                </div>
                @if ($video->editable())
                </form>
                @endif
            </div>

            <comments :video="{{ $video }}"></comments>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" />
    <style>
        .video-js {
            width: 100%;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script>
    <script src="{{ asset('js/player.js') }}"></script>
    <script>
        window.CURRENT_VIDEO = '{{ $video->id }}';
    </script>
@endsection
