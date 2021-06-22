<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 月請購_站位 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "月請購_站位";

    protected $primaryKey = ['料號','客戶別','機種','製程'];

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '料號',
        '客戶別',
        '機種',
        '製程',
        '當月站位人數',
        '當月開線數',
        '當月開班數',
        '當月每人使用量',
        '當月更換頻率',
        '下月站位人數',
        '下月開線數',
        '下月開班數',
        '下月每人使用量',
        '下月更換頻率',
    ];
}
