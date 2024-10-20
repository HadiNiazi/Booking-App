<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Event\CreateRequest;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all(); // with()

        return view('auth.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
                'price' => $request->price,
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
