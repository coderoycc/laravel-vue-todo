<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validacion del request al crear una nueva tarea.
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'expires' => 'nullable|date',
            'tags' => 'string',
        ];
    }

    /**
     * Mensajes personalizados
     */
    public function messages()
    {
        return [
            'title.required' => 'El título es obligatorio.',
        ];
    }
}
