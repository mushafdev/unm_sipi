<?php

namespace App\Http\Controllers\a;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use Throwable;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class RoomController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:room-list', only : ['index','get_room','get_data']),
            new Middleware('permission:room-create', only : ['store']),
            new Middleware('permission:room-edit', only : ['store']),
            new Middleware('permission:room-delete', only : ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $title='Room';
        $rooms = Room::with('beds')->get();
        return view('a.pages.rooms.index', compact('rooms','title'));
    }

    public function create()
    {
        $title='Tambah Room';
        return view('a.pages.rooms.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|unique:rooms,code',
            'lantai' => 'required|string|max:2',
            'description' => 'nullable|string',
            'beds.*.bed_number' => 'required|string',
            'beds.*.status' => 'required|in:available,occupied,maintenance',
            'beds.*.notes' => 'nullable|string',
        ]);

        $room = Room::create($validated);

        foreach ($request->beds as $bed) {
            $room->beds()->create($bed);
        }

        return redirect()->route('rooms.index')->with('success', 'Room created.');
    }

    public function edit($id)
    {
        $title = 'Edit Room';
        $room = Room::with('beds')->findOrFail(decrypt0($id));
        return view('a.pages.rooms.edit', compact('room', 'title'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail(decrypt0($id));

        $validated = $request->validate([
            'name' => 'required|string',
            'code' => 'required|unique:rooms,code,' . $room->id,
            'lantai' => 'required|string|max:2',
            'description' => 'nullable|string',
            'beds.*.id' => 'nullable|exists:beds,id',
            'beds.*.bed_number' => 'required|string',
            'beds.*.status' => 'required|in:available,occupied,maintenance',
            'beds.*.notes' => 'nullable|string',
        ]);

        $room->update($validated);

        // Sync beds: hapus semua dan tambah ulang (atau bisa pakai logika update)
        $room->beds()->delete();
        foreach ($request->beds as $bed) {
            $room->beds()->create($bed);
        }

        return redirect()->route('rooms.index')->with('success', 'Room updated.');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail(decrypt0($id));
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted.');
    }
}
