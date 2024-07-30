<?php

namespace App\Models;

use Illuminate\Support\Arr;

trait RecordsActivity
{
    public $oldAttributes = [];

    public static function bootRecordsActivity() //a common convention for the boot to be called on a trait
    {
        static::updating(function ($model) {
            $model->oldAttributes = $model->getOriginal();
        });

        $recordableEvents = ['created', 'updated', 'deleted'];

        foreach ($recordableEvents as $event) {
            static::$event(function ($model) use ($event) {
                $action = $event.'_'.strtolower(class_basename($model));
                if ($action === 'updated_task') {
                    $model->updateTask($model);
                } elseif ($action === 'deleted_project') {
                    return;
                } else {
                    $model->recordActivity($action);
                }
            });
        }
    }

    public function updateTask($model)
    {
        if ($model->wasChanged('completed')) {
            if ($model->completed) {
                $model->recordActivity('completed_task');
            } else {
                $model->recordActivity('uncompleted_task');
            }
        } elseif ($model->wasChanged('body')) {
            $model->recordActivity('updated_task');
        }
    }

    public function recordActivity($action): void
    {
        $this->activity()->create([
            'action' => $action,
            'changes' => $this->activityChanges(),
            'project_id' => ($this->project ?? $this)->id,
            'user_id' => ($this->project ?? $this)->owner_id,
        ]);
    }

    public function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => Arr::except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at'),
            ];
        }
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }
}
