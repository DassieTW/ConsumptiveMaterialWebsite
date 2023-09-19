<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outbound extends Model
{
    use HasFactory;
    //use SoftDeletes;

    public $timestamps = false;
    protected $table = "outbound";

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        // '客戶別',
        // '機種',
        // '製程',
        '領用原因',
        '線別',
        '料號',
        '品名',
        '規格',
        '單位',
        '預領數量',
        '實際領用數量',
        '備註',
        '實領差異原因',
        '儲位',
        '領料人員',
        '領料人員工號',
        '發料人員',
        '發料人員工號',
        '領料單號',
        '開單時間',
        '出庫時間',
    ];
}