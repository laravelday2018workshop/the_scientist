<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ArticleIDRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'string', new ArticleIDRule()],
        ];
    }

    protected function validationData(): array
    {
        return $this->route()->parameters();
    }

    /**
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json($validator->errors(), 404));
    }
}
