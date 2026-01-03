<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function frontend()
    {
        return view('frontend');
    }

    public function admin()
    {
        return view('admin');
    }

    public function fetchEvents()
    {
        $today = Carbon::now()->format('Y-m-d');
        $data = [
            'today' => Event::whereDate('date', $today)->orderBy('time')->get(),
            'future' => Event::whereDate('date', '>', $today)->orderBy('date')->get(),
            'past' => Event::whereDate('date', '<', $today)->orderBy('date', 'desc')->get(),
            'all' => Event::orderBy('date', 'desc')->get()
        ];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date'
        ]);

        Event::create($request->all());
        return response()->json(['message' => 'Event added successfully!']);
    }

    public function edit($id)
    {
        return response()->json(Event::find($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date'
        ]);

        Event::find($id)->update($request->all());
        return response()->json(['message' => 'Event updated successfully!']);
    }

    public function destroy($id)
    {
        Event::destroy($id);
        return response()->json(['message' => 'Event deleted successfully!']);
    }
}