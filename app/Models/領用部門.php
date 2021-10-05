<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 領用部門 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "領用部門";

    protected $primaryKey = '領用部門';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '領用部門',
    ];
}
