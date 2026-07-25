<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(fn (Model $model) => self::recordAudit($model, 'created', null, $model->getAttributes()));
        static::updated(fn (Model $model) => self::recordAudit($model, 'updated', $model->getOriginal(), $model->getChanges()));
        static::deleted(fn (Model $model) => self::recordAudit($model, 'deleted', $model->getOriginal(), null));
    }

    private static function recordAudit(Model $model, string $event, ?array $before, ?array $after): void
    {
        $hidden = array_merge($model->getHidden(), ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes']);

        AuditLog::query()->create([
            'actor_id' => auth()->id(),
            'subject_type' => $model->getMorphClass(),
            'subject_id' => $model->getKey(),
            'event' => $event,
            'before' => $before ? Arr::except($before, $hidden) : null,
            'after' => $after ? Arr::except($after, $hidden) : null,
            'ip_address' => request()?->ip(),
            'user_agent' => str(request()?->userAgent())->limit(500)->toString(),
        ]);
    }
}
