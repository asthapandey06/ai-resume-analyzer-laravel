<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreResumeRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'resume' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120' //kilobytes
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'resume.required' => 'Please upload your resume.',
            'resume.file' => 'The uploaded item must be a valid file.',
            'resume.mimes' => 'Only PDF files are allowed.',
            'resume.max' => 'Resume size must not exceed 5 MB.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        dd($validator->errors()->toArray());

        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
