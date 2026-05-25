<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
    ];

    public $casts = [
        'phone' => E164PhoneNumberCast::class.':UA'
    ];

    // for temporary initialization
    public $temp_name;
    public $temp_email;

    /**
     * Static method automatically called after the model is initialized
     *
     * @return void
     */
    protected static function booted(): void
    {
        // Trigger before create Customer
        static::creating(function (Customer $customer) {

            // if "temp_name" and "temp_email" are set
            if (isset($customer->temp_name) && isset($customer->temp_email)) {

                $user = User::create([
                    'name' => $customer->temp_name,
                    'email' => $customer->temp_email,
                    'password' => Hash::make('password'),
                ]);
                // Role customer
                $user->assignRole('customer');

                // assign Relation
                $customer->user_id = $user->id;
            }
        });
    }

    /**
     * Get the user that owns the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: allows writing $customer->name
     */
    public function getNameAttribute(): string
    {
        return $this->user?->name ?? '';
    }

    /**
     * Accessor: allows writing $customer->email
     */
    public function getEmailAttribute(): string
    {
        return $this->user?->email ?? '';
    }
}
