<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DententionRequest extends FormRequest
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
        $containervalidated=in_array('all', $this->container_ids) ? 'in:all':'exists:containers,id';
        return [
            'booking_no' => 'required|exists:booking,id',
            'container_ids' => 'required|array|min:1|'.$containervalidated,
            'from' => 'required|exists:containers_movement,id',
            'to' => 'nullable|exists:containers_movement,id',
            'to_date' => 'nullable|date',
            'apply_first_day' => 'nullable|boolean',
            'apply_last_day' => 'nullable|boolean',
        ];
    }
}