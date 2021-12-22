<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 接收明細 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = "接收明細";


    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '調撥單號',
        '客戶別',
        '撥出廠區',
        '接收廠區',
        '料號',
        '品名',
        '規格',
        '實際接收數量',
        '實際撥出數量',
        '儲位',
        '接收人',
        '接收時間',
    ];
}
