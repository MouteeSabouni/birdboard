<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateProjectRequest extends FormRequest
{
    public function project()
    {
        return Project::findOrFail($this->route('project'));
    }

    public function authorize(): bool
    {

        return Gate::allows('update', $this->project());
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:100',
            'description' => 'sometimes|required|string|min:5',
            'notes' => 'nullable|string|min:3|max:255',
        ];
    }

    public function save()
    {
        return tap($this->project())->update($this->validated());
    }
}
