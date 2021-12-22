<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 領用原因 extends Model
{

    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    protected $table = "領用原因";

    protected $primaryKey = '領用原因';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '領用原因',
    ];
}
