<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_no' => 'required|string|max:255|unique:rooms,room_no',
            'capacity' => 'required|integer|min:1',
        ]);

        Room::create($request->only('room_no', 'capacity'));

        return redirect()->route('admin.rooms.index')
                         ->with('success', 'Room added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_no' => 'required|string|max:255|unique:rooms,room_no,' . $room->id,
            'capacity' => 'required|integer|min:1',
        ]);

        $room->update($request->only('room_no', 'capacity'));

        return redirect()->route('admin.rooms.index')
                         ->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')
                         ->with('success', 'Room deleted successfully!');
    }
}
