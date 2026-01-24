<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $guestRules = [];

        if(! auth()->check()) {
            $guestRules = [
                'name' => 'required | min:3 | max:255',
                'email' => 'required | email | max:255'
            ];
        }
        return array_merge([
            'content' => 'required | min:2',
            'parent_id'=>'nullable|exists:comments,id'
        ], $guestRules);
    }
}
