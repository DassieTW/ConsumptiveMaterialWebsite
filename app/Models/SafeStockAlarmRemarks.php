<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafeStockAlarmRemarks extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "safestock報警備註";

    protected $primaryKey = ['料號'];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        '料號',
        // '客戶別',
        '備註',
    ];
}