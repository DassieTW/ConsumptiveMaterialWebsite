<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class O庫Material extends Model
{
    use HasFactory;
    //use SoftDeletes;

    protected $table = "O庫_material";

    protected $primaryKey = '料號';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '料號',
        '品名',
        '規格',
    ];
}
