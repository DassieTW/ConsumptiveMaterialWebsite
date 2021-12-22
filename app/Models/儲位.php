<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 儲位 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = "儲位";

    protected $primaryKey = '儲存位置';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '儲存位置',
    ];
}
