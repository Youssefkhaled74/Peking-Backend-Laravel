<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryBoyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
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
            'name'                  => ['required', 'string', 'max:190'],
            'email'                 => [
                'required',
                'email',
                'max:190',
                Rule::unique("users", "email")->ignore($this->route('deliveryBoy.id'))
            ],
            'password'              => [
                $this->route('deliveryBoy.id') ? 'nullable' : 'required',
                'string',
                'min:6',
                'confirmed'
            ],
            'password_confirmation' => [$this->route('deliveryBoy.id') ? 'nullable' : 'required', 'string', 'min:6'],
            'username'              => [
                'nullable',
                'max:190',
                Rule::unique("users", "username")->ignore($this->route('deliveryBoy.id'))
            ],
            'device_token'          => ['nullable', 'string'],
            'web_token'             => ['nullable', 'string'],
            'phone'                 => [
                'nullable',
                'string',
                'max:20',
                Rule::unique("users", "phone")->ignore($this->route('deliveryBoy.id'))
            ],
            'branch_id'             => ['nullable', 'numeric'],
            'status'                => ['required', 'numeric', 'max:24'],
            'country_code'          => ['nullable', 'string', 'max:20'],
        ];
    }
}
