<?php
namespace App\Interfaces;

use App\Models\Space;

interface SpaceRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Space $space, array $data);
    public function delete(Space $space);
    public function getSpaceIdsByRoomId(int $roomId);
}
