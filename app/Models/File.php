<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class File extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'title',
        'department_id',
        'status',
    ];

    /**
     * The file status constants.
     */
    const STATUS_AVAILABLE = 'Available';
    const STATUS_BORROWED = 'Dalam Pinjaman';
    const STATUS_OVERDUE = 'Belum Dikembalikan';

    /**
     * Get the department that owns the file.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the borrowings for the file.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Scope a query to only include available files.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope a query to only include borrowed files.
     */
    public function scopeBorrowed($query)
    {
        return $query->where('status', self::STATUS_BORROWED);
    }

    /**
     * Scope a query to only include overdue files.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE);
    }
}
