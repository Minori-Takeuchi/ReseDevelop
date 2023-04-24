<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        if($this->getMethod() == 'PUT') {
            return [
                'date' => 'date|required|after_or_equal:tomorrow',
                'time' => [
                    'required',
                    Rule::in(['11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00','20:30'])
                ],
                'number' => 'integer|between:1,10|required',
            ];
        } else {
            return [
                'user_id' => 'required',
                'course_id' => 'required',
                'date' => 'date|required|after_or_equal:today',
                'time' => [
                    'required',
                    Rule::in(['11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00','20:30'])
                ],
                'number' => 'integer|between:1,10|required',
            ];
        }
    }
}
