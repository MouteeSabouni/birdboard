<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    use RecordsActivity;

    protected $guarded = [];

    protected $touches = ['project']; //Touch the parent class and change its updated_at column

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function complete()
    {
        $this->update(['completed' => true]);
    }

    public function uncomplete()
    {
        $this->update(['completed' => false]);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function path(): string
    {
        return "/projects/{$this->project->id}/tasks/$this->id";
    }
}
