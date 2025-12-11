<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Announcement;

class NewAnnouncementEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('announcements'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'announcement.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->announcement->id,
            'judul' => $this->announcement->judul,
            'isi' => $this->announcement->isi,
            'created_at' => $this->announcement->created_at->format('d/m/Y H:i'),
        ];
    }
}