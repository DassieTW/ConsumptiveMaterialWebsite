<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 請購單 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    protected $table = "請購單";

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        'SRM單號',
        '客戶',
        '料號',
        '品名',
        'MOQ',
        '下月需求',
        '當月需求',
        '安全庫存',
        '單價',
        '幣別',
        '匯率',
        '在途數量',
        '現有庫存',
        '本次請購數量',
        '實際需求',
        '請購金額',
        '請購占比',
        '需求金額',
        '需求占比',
        '請購時間',
        'SXB單號',
    ];
}
