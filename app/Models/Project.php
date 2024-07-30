<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 * @method static findOrFail(mixed $project)
 *
 * @property mixed $owner
 */
class Project extends Model
{
    use HasFactory;
    use RecordsActivity;

    protected $guarded = [];

    public function path(): string
    {
        return "/projects/$this->id";
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body): Model
    {
        return $this->tasks()->create(['body' => $body]);
    }

    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class)->latest();
    }
}
