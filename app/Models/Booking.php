<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'user_id',
        'room_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'check_in',
        'check_out',
        'guests',
        'special_requests',
        'status',
        'total_amount',
        'payment_status',
        'payment_reference',
        'payment_method',
        'payment_paid_at',
        'payment_expires_at',
        'check_in_time',
        'check_out_time',
        'check_in_at',
        'check_out_at',
        'cancellation_reason',
        'cancelled_by',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'total_amount' => 'decimal:2',
            'check_in_time' => 'string',
            'check_out_time' => 'string',
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
            'payment_paid_at' => 'datetime',
            'payment_expires_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function getCheckInDateTimeAttribute(): ?\Illuminate\Support\Carbon
    {
        if ($this->check_in_at) {
            return $this->check_in_at;
        }

        if (empty($this->check_in)) {
            return null;
        }

        $time = $this->check_in_time ?: '00:00:00';

        // parse in app timezone and return as Carbon (app timezone)
        return \Illuminate\Support\Carbon::parse($this->check_in.' '.$time, config('app.timezone'));
    }

    public function getCheckOutDateTimeAttribute(): ?\Illuminate\Support\Carbon
    {
        if ($this->check_out_at) {
            return $this->check_out_at;
        }

        if (empty($this->check_out)) {
            return null;
        }

        $time = $this->check_out_time ?: '00:00:00';

        return \Illuminate\Support\Carbon::parse($this->check_out.' '.$time, config('app.timezone'));
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getNightsAttribute(): int
    {
        return Carbon::parse($this->check_in)->diffInDays(Carbon::parse($this->check_out));
    }
}