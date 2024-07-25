<?php
namespace App\Repositories;

use App\Interfaces\SpaceRepositoryInterface;
use App\Models\Space;

class SpaceRepository implements SpaceRepositoryInterface
{
    public function all()
    {
        return Space::all();
    }

    public function find($id)
    {
        return Space::findOrFail($id);
    }

    public function create(array $data)
    {
        return Space::create($data);
    }

    public function update(Space $space, array $data)
    {
        $space->update($data);
        return $space;
    }

    public function delete(Space $space)
    {
        $space->delete();
        return true;
    }

    public function getSpaceIdsByRoomId(int $roomId)
    {
        return Space::where('room_id', $roomId)
            ->pluck('id')->toArray();
    }
}
