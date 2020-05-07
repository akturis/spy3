<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UserRolesFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'role_role_id' => 'nullable',
            'name' => 'string|min:1|max:255|nullable',
            'user_id' => 'nullable',
        ];

        return $rules;
    }
    
    /**
     * Get the request's data from the request.
     *
     * 
     * @return array
     */
    public function getData()
    {
        $data = $this->only(['role_role_id', 'name', 'user_id']);

        return $data;
    }

}