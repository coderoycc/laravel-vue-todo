<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'expires' => 'nullable|date',
            'tags' => 'string',
            'status' => 'string|in:HECHO,PENDIENTE'
        ];
    }

    /**
     * Mensajes personalizados
     */
    public function messages()
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'status.in' => 'El estado debe ser HECHO o PENDIENTE'
        ];
    }
}
