<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\AcademicIDRule;
use App\Rules\ArticleBodyRule;
use App\Rules\ArticleTitleRule;
use App\Rules\ReviewerIDRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateArticleRequest extends FormRequest
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
            'reviewer_id' => ['required', 'string', new ReviewerIDRule()],
            'academic_id' => ['required', 'string', new AcademicIDRule()],
        ];
    }

    /**
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
