<?php
namespace App\Interfaces;

use App\Models\Room;

interface RoomRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Room $room, array $data);
    public function delete(Room $room);
}
