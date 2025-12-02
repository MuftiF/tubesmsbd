<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Announcement;

class NewAnnouncementEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function broadcastOn()
    {
        return new Channel('announcements'); // nama channel broadcast
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->announcement->id,
            'judul' => $this->announcement->judul,
            'isi' => $this->announcement->isi,
            'created_at' => $this->announcement->created_at->toDateTimeString(),
        ];
    }
}
