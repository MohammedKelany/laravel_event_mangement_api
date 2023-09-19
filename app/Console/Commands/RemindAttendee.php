<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventRemiderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RemindAttendee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remind-attendee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::whereBetween("start_at", [now(), now()->addDay()])->with("attendees.user")->get();
        $count = $events->count();
        $plural = Str::plural("event", $count);
        $this->info("Found " . $count . " " . $plural);
        $events->each(
            fn ($event) => $event->attendees
                ->each(
                    fn ($attendee) => $attendee->user->notify(
                        new EventRemiderNotification($event)
                    )
                )
        );
        $this->info("Reminding Users sent Successfully !!");
    }
}
