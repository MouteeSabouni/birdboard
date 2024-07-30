<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ProjectInvitationRequest extends FormRequest
{
    protected $errorBag = 'invitations';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('manage', $this->route('project'));
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function messages() //this is an override.
    {
        return [
            'email.exists' => 'The invited user must have a birdboard account.',
        ];
    }
}
