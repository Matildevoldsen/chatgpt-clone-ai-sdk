<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'chat' => ['nullable', 'string'],
            'message' => ['required', 'string', 'max:8000'],
            'model' => ['sometimes', 'string', 'in:gpt-5.4,gpt-5.4-mini'],
        ];
    }
}
