<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T24Data extends Model
{
    use HasFactory;
    protected $table = "t24_mlcondition";
//    protected $primaryKey = ['id'];

//    public $timestamps = false;
//    public $incrementing = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
