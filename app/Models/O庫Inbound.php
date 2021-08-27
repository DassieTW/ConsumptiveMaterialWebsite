<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class O庫Inbound extends Model
{
    use HasFactory;
    use SoftDeletes;

<<<<<<< HEAD
    protected $table = "O庫Inbound";
=======
    protected $table = "O庫inbound";
>>>>>>> 0827tony

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
<<<<<<< HEAD
        '料號',
        '品名',
        '規格',
        '廠別',
=======
        '入庫單號',
        '料號',
        '品名',
        '規格',
        '客戶別',
>>>>>>> 0827tony
        '庫別',
        '數量',
        '入庫人員',
        '時間',
        '備註',
<<<<<<< HEAD
=======
        '入庫原因',
>>>>>>> 0827tony
    ];
}
