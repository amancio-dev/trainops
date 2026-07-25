<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnualBudget extends Model
{
    use Auditable, HasFactory;

    protected $fillable = ['year', 'amount', 'owner_id'];

    protected function casts(): array
    {
        return ['year' => 'integer', 'amount' => 'decimal:2'];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }
}
