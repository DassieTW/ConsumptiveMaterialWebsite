<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 撥出明細 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = "撥出明細";


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
        '現有庫存',
        '預計撥出數量',
        '實際撥出數量',
        '儲位',
        '調撥人',
        '撥出時間',
    ];
}
