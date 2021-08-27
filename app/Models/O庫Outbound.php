<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class O庫Outbound extends Model
{
    use HasFactory;
    use SoftDeletes;

<<<<<<< HEAD
    protected $table = "O庫Outbound";
=======
    protected $table = "O庫outbound";
>>>>>>> 0827tony

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
<<<<<<< HEAD
        '料號',
        '品名',
        '規格',
        '廠別',
        '庫別',
        '數量',
        '領料人',
        '時間',
        '備註',
=======
        '客戶別',
        '機種',
        '製程',
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
        '庫別',
        '領料人員',
        '領料人員工號',
        '發料人員',
        '發料人員工號',
        '領料單號',
        '開單時間',
        '出庫時間',
>>>>>>> 0827tony
    ];
}
