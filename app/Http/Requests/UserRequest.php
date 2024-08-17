<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->isMethod('POST')) {

        return [
            'prefixname' => ['nullable','in:Mr,Mrs,Ms', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable','string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'suffixname' => ['nullable','string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'photo' => ['nullable', 'mimes:png,jpg,jpeg'],
            'type' => ['nullable','string'],
        ];
    } 
    
    if ($this->isMethod('PUT')) {
        return [
            'prefixname' => ['nullable','in:Mr,Mrs,Ms', 'string', 'max:255'],
            'firstname' => ['nullable', 'string', 'max:255'],
            'middlename' => ['nullable','string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'suffixname' => ['nullable','string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'photo' => ['nullable', 'mimes:png,jpg,jpeg'],
            'type' => ['nullable','string'],
        ];
    }

    }
}
