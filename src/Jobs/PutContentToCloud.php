<?php

namespace Insowe\DataLogger\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use Storage;

class PutContentToCloud implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $path;

    protected $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $content)
    {
        $this->path = $path;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Storage::cloud()->put($this->path, $this->content);
        Log::debug("[{$this->path}] has been Uploaded.");
    }
}
