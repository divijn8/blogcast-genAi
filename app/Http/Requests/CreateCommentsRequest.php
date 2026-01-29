<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guestRules = [];

        if (! auth()->check()) {
            $guestRules = [
                'guest_name'  => 'required|min:3|max:255',
                'guest_email' => 'required|email|max:255',
            ];
        }

        return array_merge([
            // MAIN COMMENT
            'comment' => 'required|string|min:2',

            // POLYMORPHIC
            'commentable_type' => 'required|in:post,podcast',
            'commentable_id'   => 'required|integer',

            // REPLIES
            'parent_id' => 'nullable|exists:comments,id',
        ], $guestRules);
    }
}
