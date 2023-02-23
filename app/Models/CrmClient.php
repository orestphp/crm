<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CrmClient extends Authenticatable
{
    use HasFactory;

    protected $table = 'crm_clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userip',
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'client_json',
    ];

}
