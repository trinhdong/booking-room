<?php
namespace App\Interfaces;

use App\Models\Booking;

interface BookingRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(Booking $booking, array $data);
    public function delete(Booking $booking);
    public function findExistBooking($spaceIds, $startTime, $endTime);
}
