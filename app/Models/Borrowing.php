<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'file_id',
        'borrower_name',
        'borrow_date',
        'return_date',
        'officer_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * The borrowing status constants.
     */
    const STATUS_BORROWED = 'Dalam Pinjaman';
    const STATUS_RETURNED = 'Dikembalikan';
    const STATUS_OVERDUE = 'Belum Dikembalikan';

    /**
     * Get the file that was borrowed.
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * Get the officer who processed the borrowing.
     */
    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    /**
     * Scope a query to only include active borrowings.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_BORROWED, self::STATUS_OVERDUE]);
    }

    /**
     * Scope a query to only include returned borrowings.
     */
    public function scopeReturned($query)
    {
        return $query->where('status', self::STATUS_RETURNED);
    }

    /**
     * Scope a query to only include overdue borrowings.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE);
    }
}
