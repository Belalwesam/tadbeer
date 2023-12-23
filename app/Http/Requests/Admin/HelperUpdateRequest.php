<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HelperUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            'nationality' => 'required|string',
            'age' => 'required|integer',
            'category_id' => 'required|integer',
            'video' => 'nullable|mimes:mp4',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'resume' => 'nullable|mimes:pdf|max:2048',
            'religion' => 'required|string',

        ];
    }


    public function attributes()
    {
        return [
            'category_id' => trans('helpers.category'),
            'name' => trans('helpers.name'),
            'age' => trans('helpers.age'),
            'resume' => trans('helpers.resume'),
            'nationality' => trans('helpers.nationality'),
            'video' => trans('helpers.video'),
            'avatar' => trans('helpers.avatar'),
            'religion' => trans('helpers.religion'),

        ];
    }
}
