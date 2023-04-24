<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
        return [
            'area_id' => 'required',
            'genre_id' => 'required',
            'manager_id' => 'required',
            'shop_name' => 'required',
            'content' => 'max:300',
            'img' => 'file|mimes:jpeg,png,jpg,gif',
        ];
    }
}
