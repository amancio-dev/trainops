<?php

namespace App\Models;

use App\Enums\TrainingStatus;
use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Training extends Model
{
    use Auditable, HasFactory;

    protected $fillable = [
        'employee_id',
        'course_id',
        'annual_budget_id',
        'created_by',
        'institution',
        'starts_at',
        'ends_at',
        'status',
        'registration_cost',
        'lodging_cost',
        'transport_cost',
        'transfer_cost',
        'daily_allowance_cost',
        'notes',
        'cancellation_reason',
    ];

    protected $appends = ['total_cost'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date:Y-m-d',
            'ends_at' => 'date:Y-m-d',
            'status' => TrainingStatus::class,
            'registration_cost' => 'decimal:2',
            'lodging_cost' => 'decimal:2',
            'transport_cost' => 'decimal:2',
            'transfer_cost' => 'decimal:2',
            'daily_allowance_cost' => 'decimal:2',
        ];
    }

    protected function totalCost(): Attribute
    {
        return Attribute::get(fn () => collect([
            $this->registration_cost,
            $this->lodging_cost,
            $this->transport_cost,
            $this->transfer_cost,
            $this->daily_allowance_cost,
        ])->sum());
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function annualBudget(): BelongsTo
    {
        return $this->belongsTo(AnnualBudget::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
