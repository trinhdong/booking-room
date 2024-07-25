<?php
namespace App\Repositories;

use App\Interfaces\RoomRepositoryInterface;
use App\Models\Room;

class RoomRepository implements RoomRepositoryInterface
{
    public function all()
    {
        return Room::all();
    }

    public function find($id)
    {
        return Room::findOrFail($id);
    }

    public function create(array $data)
    {
        return Room::create($data);
    }

    public function update(Room $room, array $data)
    {
        $room->update($data);
        return $room;
    }

    public function delete(Room $room)
    {
        $room->delete();
        return true;
    }
}
