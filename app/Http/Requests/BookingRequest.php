<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'space_id' => 'required|integer|exists:spaces,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ];
    }

    public function messages()
    {
        return [
            'space_id.required' => 'The space ID is required.',
            'space_id.integer' => 'The space ID must be an integer.',
            'space_id.exists' => 'The selected space ID does not exist.',
            'start_time.required' => 'The start time is required.',
            'start_time.date' => 'The start time must be a valid date.',
            'start_time.after_or_equal' => 'The start time must be a future date and time.',
            'end_time.required' => 'The end time is required.',
            'end_time.date' => 'The end time must be a valid date.',
            'end_time.after' => 'The end time must be after the start time.',
        ];
    }

    protected function failedValidation(ValidatorContract $validator)
    {
        $errors = $validator->errors()->all();
        $response = ['success' => false, 'message' => $errors];

        throw new HttpResponseException(response()->json($response, 422));
    }

    public function validateBookingData(array $data): array
    {
        $validator = Validator::make($data, $this->rules(), $this->messages());

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return ['success' => false, 'message' => $errors];
        }

        return ['success' => true, 'data' => $data];
    }
}
