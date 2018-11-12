<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ArticleBodyRule;
use App\Rules\ArticleIDRule;
use App\Rules\ArticleTitleRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'string', new ArticleIDRule()],
            'title' => ['required', 'string', new ArticleTitleRule()],
            'body' => ['required', 'string', new ArticleBodyRule()],
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
