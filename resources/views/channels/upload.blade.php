@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <channel-upload :channel="{{ $channel }}" />
        </div>
    </div>
@endsection
