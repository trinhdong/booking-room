<?php

namespace Tests\Unit;

use App\Http\Requests\BookingRequest;
use App\Interfaces\BookingRepositoryInterface;
use App\Interfaces\SpaceRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use Mockery;
use App\Services\BookingService;
use App\Models\Space;
use App\Models\Booking;

class BookingServiceTest extends TestCase
{
    protected $bookingService;
    protected $bookingRepository;
    protected $spaceRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookingRepository = Mockery::mock(BookingRepositoryInterface::class);
        $this->spaceRepository = Mockery::mock(SpaceRepositoryInterface::class);

        $this->bookingService = new BookingService(
            $this->bookingRepository,
            $this->spaceRepository
        );
    }

    /** @test */
    public function it_validates_space_id_is_required()
    {
        $validator = Validator::make([], (new BookingRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('space_id'));
    }

    /** @test */
    public function it_validates_space_id_must_be_integer()
    {
        $validator = Validator::make(['space_id' => 'string'], (new BookingRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('space_id'));
    }

    /** @test */
    public function it_validates_space_id_must_exist()
    {
        $validator = Validator::make(['space_id' => 999], (new BookingRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('space_id'));
    }

    /** @test */
    public function it_validates_start_time_is_required()
    {
        $validator = Validator::make([], (new BookingRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('start_time'));
    }

    /** @test */
    public function it_validates_start_time_must_be_a_valid_date()
    {
        $validator = Validator::make(['start_time' => 'invalid-date'], (new BookingRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('start_time'));
    }

    /** @test */
    public function it_validates_start_time_must_be_after_now()
    {
        $validator = Validator::make(['start_time' => now()->subDay()], (new BookingRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('start_time'));
    }

    /** @test */
    public function it_validates_end_time_is_required()
    {
        $validator = Validator::make([], (new BookingRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('end_time'));
    }

    /** @test */
    public function it_validates_end_time_must_be_a_valid_date()
    {
        $validator = Validator::make(['end_time' => 'invalid-date'], (new BookingRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('end_time'));
    }

    /** @test */
    public function it_validates_end_time_must_be_after_start_time()
    {
        $startTime = now()->addHour()->format('Y-m-d H:i:s');
        $validator = Validator::make(['start_time' => $startTime, 'end_time' => $startTime], (new BookingRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('end_time'));
    }

    /** @test */
    public function it_creates_a_booking_successfully_when_no_exist()
    {
        $spaceId = 1;
        $startTime = '2025-07-25 09:00:00';
        $endTime = '2025-07-25 12:00:00';
        $data = ['space_id' => $spaceId, 'start_time' => $startTime, 'end_time' => $endTime];

        $space = new Space(['id' => $spaceId, 'room_id' => 1]);

        $this->spaceRepository->shouldReceive('find')
            ->with($spaceId)
            ->andReturn($space);

        $this->spaceRepository->shouldReceive('getSpaceIdsByRoomId')
            ->with($spaceId)
            ->andReturn([$spaceId]);

        $this->bookingRepository->shouldReceive('findExistBooking')
            ->with([$spaceId], $startTime, $endTime)
            ->andReturn(collect([]));

        $this->bookingRepository->shouldReceive('create')
            ->with($data)
            ->andReturn(new Booking($data));

        $result = $this->bookingService->bookSpace($data);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('data', $result);
    }

    /** @test */
    public function it_fails_to_create_a_booking_when_exist()
    {
        $spaceId = 1;
        $startTime = '2025-07-25 09:00:00';
        $endTime = '2025-07-25 12:00:00';
        $data = ['space_id' => $spaceId, 'start_time' => $startTime, 'end_time' => $endTime];

        $space = new Space(['id' => $spaceId, 'room_id' => 1]);

        $this->spaceRepository->shouldReceive('find')
            ->with($spaceId)
            ->andReturn($space);

        $this->spaceRepository->shouldReceive('getSpaceIdsByRoomId')
            ->with($spaceId)
            ->andReturn([$spaceId]);

        $this->bookingRepository->shouldReceive('findExistBooking')
            ->with([$spaceId], $startTime, $endTime)
            ->andReturn(collect([new Booking($data)]));

        $result = $this->bookingService->bookSpace($data);

        $this->assertFalse($result['success']);
        $this->assertEquals('The room is already booked for the specified time range.', $result['message']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
