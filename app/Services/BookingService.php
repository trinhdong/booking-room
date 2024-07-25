<?php
namespace App\Services;

use App\Http\Requests\BookingRequest;
use App\Interfaces\BookingRepositoryInterface;
use App\Interfaces\SpaceRepositoryInterface;

class BookingService
{
    protected $bookingRepository;
    protected $spaceRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository, SpaceRepositoryInterface $spaceRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->spaceRepository = $spaceRepository;
    }

    public function bookSpace(array $data)
    {
        try {
            $space = $this->spaceRepository->find(intval($data['space_id']));
            $data['room_id'] = $space->room_id;
            return $this->checkAndCreateBooking($data);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
        }
    }

    public function bulkBookSpaces(array $bookings)
    {
        $bookingRs = [];
        try {
            foreach ($bookings as $bookingData) {
                $result = $this->checkAndCreateBooking($bookingData);

                if (!$result['success']) {
                    $bookingRs[] = $result;
                    continue;
                }

                $result['message'] = 'Bulk booking successful';
                $bookingRs[] = $result;
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
        }
        return ['success' => true, 'data' => $bookingRs];
    }

    private function checkAndCreateBooking(array $bookingData)
    {
        $validationResult = (new BookingRequest)->validateBookingData($bookingData);
        if (!$validationResult['success']) {
            return $validationResult;
        }
        $roomId = intval($bookingData['room_id']);
        $spacesInRoom = $this->spaceRepository->getSpaceIdsByRoomId($roomId);
        if (empty($spacesInRoom)) {
            return ['success' => false, 'message' => 'No spaces available in the specified room.'];
        }
        $existingBookings = $this->bookingRepository->findExistBooking($spacesInRoom, $bookingData['start_time'], $bookingData['end_time']);
        if ($existingBookings->isNotEmpty()) {
            return ['success' => false, 'message' => 'The room is already booked for the specified time range.'];
        }

        $booking = $this->bookingRepository->create($bookingData);
        return ['success' => true, 'data' => $booking];
    }

}
