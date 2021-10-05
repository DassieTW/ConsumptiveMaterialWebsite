<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class O庫出庫退料 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "O庫出庫退料";

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '客戶別',
        '機種',
        '製程',
        '退回原因',
        '線別',
        '料號',
        '品名',
        '規格',
        '預退數量',
        '實際退回數量',
        '備註',
        '實退差異原因',
        '庫別',
        '收料人員',
        '收料人員工號',
        '退料人員',
        '退料人員工號',
        '退料單號',
        '開單時間',
        '入庫時間',
        '功能狀況',
    ];
}
