<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Event\CreateRequest;
use App\Http\Requests\Auth\Event\UpdateRequest;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Booking;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role == 'user') {
            $bookings = Booking::where('user_id', $user->id)->where('status', 'paid')->pluck('event_id');

            $events = Event::whereIn('id', $bookings)->get();
        }
        else {
            $events = Event::all(); // with()
        }

        return view('auth.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        auth()->user()->checkRoleOrAbort();

        $categories = Category::all();

        return view('auth.events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $category = Category::find($request->category);

        if (! $category) {
            return back()->withErrors('Unable to find the category, Please choose the correct value.');
        }

        try {
            Event::create([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $category ? $category->id: null,
                'location' => $request->location,
                'type' => $request->type,
                'price' => $request->price ? $request->price: 0,
                'start_date' => $request->start_date, // 2024-10-15
                'end_date' => $request->end_date,
                'max_attendees' => $request->max_attendees
            ]);

            session()->flash('success_msg', 'Event Saved Successfully!');

            // return redirect()->route('events.index');
            return to_route('events.index');
        }
        catch(\Exception $ex) {
            return back()->withInput()->withErrors('Something went wrong, the error is: '. $ex->getMessage());
            // withInput
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // $event = Event::findOrFail($id);

        return view('auth.events.show', ['event' => $event]);
        // return view('auth.events.show')->with('event', $event);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        auth()->user()->checkRoleOrAbort();

        $categories = Category::all();

        return view('auth.events.edit', compact('categories', 'event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Event $event)
    {
        auth()->user()->checkRoleOrAbort();

        $category = Category::find($request->category);

        if (! $category) {
            return back()->withErrors('Unable to find the category, Please choose the correct value.');
        }

        try {
            $event->update([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $category ? $category->id: null,
                'location' => $request->location,
                'type' => $request->type,
                'price' => $request->price,
                'start_date' => $request->start_date, // 2024-10-15
                'end_date' => $request->end_date,
                'max_attendees' => $request->max_attendees,
                // addition columns here
            ]);

            session()->flash('success_msg', 'Post Updated Successfully!');

            return to_route('events.index');
        }
        catch(\Exception $ex) {
            return back()->withInput()->withErrors('Something went wrong, the error is: '. $ex->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        auth()->user()->checkRoleOrAbort();

        try {
            $event->delete();

            session()->flash('success_msg', 'Event Removed Successfully!');

            return to_route('events.index');
        }
        catch(\Exception $ex) {
            return back()->withErrors('Something went wrong! '. $ex->getMessage());
        }
    }
}
