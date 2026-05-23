<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class Customer extends Model
{
    use HasFactory;

    // TODO: 'name' and 'email' transfer to 'Users' and give Role "customer"
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public $casts = [
        'phone' => E164PhoneNumberCast::class.':UA'
    ];
}
