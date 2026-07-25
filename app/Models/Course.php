<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use Auditable, HasFactory;

    protected $fillable = ['training_type_id', 'name', 'workload_hours', 'description', 'active'];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'workload_hours' => 'integer'];
    }

    public function trainingType(): BelongsTo
    {
        return $this->belongsTo(TrainingType::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }
}
