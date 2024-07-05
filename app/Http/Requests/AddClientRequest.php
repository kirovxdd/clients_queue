<?php

namespace App\Http\Requests;

use App\Rules\IdIsValid;
use Illuminate\Foundation\Http\FormRequest;

class AddClientRequest extends FormRequest
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
        return [
            'id'      => ['required_without_all:name, surname', new IdIsValid],
            'name'    => ['required_without:id'],
            'surname' => ['required_with:name'],
        ];
    }
}
