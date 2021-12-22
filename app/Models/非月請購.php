<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 非月請購 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    protected $table = "非月請購";

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '客戶別',
        '料號',
        '請購數量',
        '上傳時間',
        '說明',
        'SXB單號',
    ];
}
