<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $query = request()->search;

        $perPage = 1;

        if ($query) {
            $videos = Video::where('title', 'LIKE', "%{$query}%")->orWhere('description', 'LIKE', "%{$query}%")->paginate($perPage);
            $channels = Channel::where('name', 'LIKE', "%{$query}%")->orWhere('description', 'LIKE', "%{$query}%")->paginate($perPage, ['*'], 'channel_page');
        } else {
            $videos = Video::where('percentage', 100)->orderBy('created_at', 'DESC')->paginate($perPage);
            $channels = Channel::orderBy('created_at', 'DESC')->paginate($perPage, ['*'], 'channel_page');
        }

        return view('home')->with([
            'videos' => $videos,
            'channels' => $channels
        ]);
    }
}
