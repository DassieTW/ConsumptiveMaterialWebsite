<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class O庫Inventory extends Model
{
    use HasFactory;
    use SoftDeletes;

<<<<<<< HEAD
    protected $table = "O庫Inventory";
=======
    protected $table = "O庫inventory";

    protected $primaryKey = ['料號' , '客戶別','庫別'];
>>>>>>> 0827tony

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '料號',
        '現有庫存',
<<<<<<< HEAD
        '廠別',
=======
        '客戶別',
>>>>>>> 0827tony
        '庫別',
        '最後更新時間',
        '品名',
        '規格',
    ];
}
