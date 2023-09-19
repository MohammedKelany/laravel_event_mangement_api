<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->except(["index", "show"]);
        $this->authorizeResource(Attendee::class, "attendee");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        return AttendeeResource::collection(
            $event->attendees()->paginate(),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = new Attendee();
        $attendee->user_id = $request->user()->id;
        return $event->attendees()->save($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $event, Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Attendee $attendee)
    {
        $attendee->delete();
        return response()->json([
            "status" => 200,
            "message" => "Event Deleted Successfully !!",
        ], 200);
    }
}
