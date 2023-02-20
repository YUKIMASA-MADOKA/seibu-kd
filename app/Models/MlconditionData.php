<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlconditionData extends Model
{
    use HasFactory;
    protected $table = "trn_mlcondition";
//    protected $primaryKey = ['id'];

//    public $timestamps = false;
//    public $incrementing = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
