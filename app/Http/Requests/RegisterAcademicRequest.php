<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\AcademicBirthDateRule;
use App\Rules\AcademicEmailRule;
use App\Rules\AcademicFirstNameRule;
use App\Rules\AcademicLastNameRule;
use App\Rules\AcademicMajorRule;
use App\Rules\AcademicPasswordRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

final class RegisterAcademicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstName' => ['required', 'string', new AcademicFirstNameRule()],
            'lastName' => ['required', 'string', new AcademicLastNameRule()],
            'email' => ['required', 'string', new AcademicEmailRule()],
            'password' => ['required', 'string', new AcademicPasswordRule()],
            'birthDate' => ['required', 'string', new AcademicBirthDateRule()],
            'major' => ['required', 'string', new AcademicMajorRule()],
        ];
    }

    protected function validationData(): array
    {
        return \array_merge($this->route()->parameters(), $this->all());
    }

    /**
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
