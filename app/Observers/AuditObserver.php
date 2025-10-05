<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    public function created($model)
    {
        $this->record('created', $model);
    }

    public function updated($model)
    {
        $changes = $model->getChanges();
        $this->record('updated', $model, $changes);
    }

    public function deleted($model)
    {
        $this->record('deleted', $model);
    }

    protected function record($action, $model, $data = [])
    {
        // Only record audit logs if user is authenticated
        if (!Auth::check()) {
            return;
        }
        
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'entity_type' => get_class($model),
            'entity_id'   => $model->id ?? null,
            'data'        => $data,
            'performed_at'=> now(),
        ]);
    }
}
