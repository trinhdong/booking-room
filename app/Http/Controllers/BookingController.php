<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    protected $bookingService;
    protected $apiResponseClass;

    public function __construct(BookingService $bookingService, ApiResponseClass  $apiResponseClass)
    {
        $this->bookingService = $bookingService;
        $this->apiResponseClass = $apiResponseClass;
    }

    public function bookSpace(BookingRequest $request)
    {
        DB::beginTransaction();

        try {
            $result = $this->bookingService->bookSpace($request->input());

            if ($result['success']) {
                $booking = $this->apiResponseClass->sendResponse(new BookingResource($result['data']), 'Booking successful');
                DB::commit();
                return $booking;
            }

            DB::rollback();
            return $this->apiResponseClass->sendError($result['message']);
        } catch (\Exception $e) {
            $this->apiResponseClass->rollback($e, 'An error occurred: ' . $e->getMessage());
        }
    }

    public function bulkBooking(Request $request)
    {
        DB::beginTransaction();

        try {
            $bookings = $request->input('bookings') ?? [];
            $bookings = array_map(function ($booking) {return json_decode($booking, true);}, $bookings);
            $result = $this->bookingService->bulkBookSpaces($bookings);
            if ($result['success']) {
                $result['data'] = array_map(function ($rs) {
                    if ($rs['success']) {
                        return $this->apiResponseClass->sendResponse(new BookingResource($rs['data']), 'Booking successful');
                    }
                    return $this->apiResponseClass->sendError($rs['message']);
                }, $result['data']);
                $bookings = $this->apiResponseClass->sendResponse($result['data'], 'Bulk booking successful');
                DB::commit();
                return $bookings;
            }

            DB::rollback();
            return $this->apiResponseClass->sendError($result['message']);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->apiResponseClass->sendError('An error occurred: ' . $e->getMessage());
        }
    }

}
