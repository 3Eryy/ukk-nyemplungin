<?php
// app/Traits/LogsActivity.php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created', $model->getOriginal(), $model->getAttributes());
        });

        static::updated(function ($model) {
            $model->logActivity('updated', $model->getOriginal(), $model->getAttributes());
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', $model->getOriginal(), null);
        });
    }

    protected function logActivity($action, $oldData = null, $newData = null)
    {
        if (!auth()->check()) return;

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action . '_' . strtolower(class_basename($this)),
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'description' => $this->getActivityDescription($action),
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected function getActivityDescription($action)
    {
        $descriptions = [
            'created' => 'Membuat ' . strtolower(class_basename($this)) . ' baru',
            'updated' => 'Mengupdate ' . strtolower(class_basename($this)),
            'deleted' => 'Menghapus ' . strtolower(class_basename($this)),
        ];

        return $descriptions[$action] ?? 'Melakukan ' . $action . ' pada ' . strtolower(class_basename($this));
    }
}