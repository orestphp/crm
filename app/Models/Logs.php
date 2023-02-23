<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * Class Logs
 * @package App\Models
 */
class Logs extends Model
{

    protected $table = 'logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'api_key',
        'crm_url',
        'json_data',
        'integration_id',// cms id
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'created_at' => 'datetime',
    ];

}
