<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inbound extends Model
{
    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    protected $table = "inbound";

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '入庫單號',
        '料號',
        '入庫數量',
        '儲位',
        '入庫人員',
        '客戶別',
        '入庫原因',
        '入庫時間',
        '備註',
    ];
}
