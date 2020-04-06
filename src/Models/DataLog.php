<?php

namespace Insowe\DataLogger\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * 資料紀錄
 */
class DataLog extends BaseModel
{
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data_type', 'data_id', 'hash',
        'administrator_id',
    ];
}
