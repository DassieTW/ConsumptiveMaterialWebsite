<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SSZInfo extends Model
{
    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    protected $table = "SSZInfo";

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
    ];

    public function number_status()
    {
        return $this->belongsTo(SSZNumber::class, 'id');
    } // number_status
} // SSZInfo
