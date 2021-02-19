<?php

namespace App\Jobs\Videos;

use App\Models\Video;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Filesystem\Filesystem;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;

class ConvertForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $low = (new X264('aac'))->setKiloBitrate(100); // 360p
        $medium = (new X264('aac'))->setKiloBitrate(250); // 360p
        $high = (new X264('aac'))->setKiloBitrate(500); // 360p

        try {
            FFMpeg::fromDisk('local')
                ->open($this->video->path)
                ->exportForHLS()
                ->onProgress(function ($percentage) {
                    $this->video->update([
                        'percentage' => $percentage
                    ]);
                })
                ->addFormat($low)
                ->addFormat($medium)
                ->addFormat($high)
                ->save("public/videos/{$this->video->id}/{$this->video->id}.m3u8");
        } catch (EncodingException $exception) {
            $command = $exception->getCommand();
            $errorLog = $exception->getErrorOutput();
            print($errorLog);
        }
    }
}
