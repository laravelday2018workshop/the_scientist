<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\AcademicRegistrationNumberRule;
use App\Rules\ArticleBodyRule;
use App\Rules\ArticleTitleRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

final class WriteArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', new ArticleTitleRule()],
            'body' => ['required', 'string', new ArticleBodyRule()],
            'id' => ['required', 'string', new AcademicRegistrationNumberRule()],
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
