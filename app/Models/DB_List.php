<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DB_List extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "DB_List";

    protected $primaryKey = '廠區';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '廠區',
        'IP',
        'DB_Name',
    ];
}
