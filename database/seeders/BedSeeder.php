<?php

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\Room;
use Illuminate\Database\Seeder;

class BedSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::all();

        foreach ($rooms as $room) {
            // Jumlah bed menyesuaikan jenis ruangan
            $jumlahBed = match ($room->code) {
                'FAC1', 'FAC2' => 2,
                'LAS1' => 1,
                'BODY1' => 3,
                default => 1,
            };

            for ($i = 1; $i <= $jumlahBed; $i++) {
                Bed::create([
                    'room_id' => $room->id,
                    'bed_number' => 'Bed ' . $i,
                    'status' => collect(['available', 'occupied', 'maintenance'])->random(),
                    'notes' => null,
                ]);
            }
        }
    }
}
