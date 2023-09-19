<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use PHPUnit\Event\EventCollection;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:sanctum")->except(["index", "show"]);
        $this->authorizeResource(Event::class, "event");
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EventResource::collection(
            Event::with("user")->paginate(),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string|min:5",
            "description" => "required|string|min:10",
            "start_at" => "required|date",
            "end_at" => "required|date|after:start_at",
        ]);
        $event = new Event();
        $event->name = $data["name"];
        $event->description = $data["description"];
        $event->start_at = $data["start_at"];
        $event->end_at = $data["end_at"];
        $event->user_id = $request->user()->id;
        $event->save();
        return new EventResource($event->load("user"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return new EventResource($event->load("user", "attendees"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // $this->authorize("update-event", $event);
        $data = $request->validate([
            "name" => "required|string|min:5",
            "description" => "required|string|min:10",
            "start_at" => "required|date",
            "end_at" => "required|date|after:start_at",
        ]);
        $event->name = $data["name"];
        $event->description = $data["description"];
        $event->start_at = $data["start_at"];
        $event->end_at = $data["end_at"];
        $event->save();
        return new EventResource($event->load("user", "attendees"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        // $this->authorize("delete-event", $event);
        $event->delete();
        return response()->json([
            "status" => 204,
            "message" => "Event Deleted Successfully !!",
        ]);
    }
}
