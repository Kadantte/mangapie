<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Manga;

class NewArchiveEvent
{
    use Dispatchable;

    public $name;
    public $path;
    public $rootPath;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($name, $path, $rootPath, $data)
    {
        $this->name = $name;
        $this->path = $path;
        $this->rootPath = $rootPath;
        $this->data = $data;
    }
}
