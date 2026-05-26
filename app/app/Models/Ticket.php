<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use \Illuminate\Database\Eloquent\Model;

class Ticket extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'text',
        'status'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->useDisk('public'); // save to - storage/app/public/
    }

    /**
     * Get the customer that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Sort by today, week, month
    public function scopeCreatedToday(Builder $query): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subDay());
    }

    public function scopeCreatedThisWeek(Builder $query): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subWeek());
    }

    public function scopeCreatedThisMonth(Builder $query): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subMonth());
    }

    // sort by Status
    public function scopeNewTickets(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function scopeInProcess(Builder $query): Builder
    {
        return $query->where('status', 'in process');
    }

    public function scopeProcessed(Builder $query): Builder
    {
        return $query->where('status', 'processed');
    }
}
