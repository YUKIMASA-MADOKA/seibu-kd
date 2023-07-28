<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrateData extends Model
{
    use HasFactory;
    protected $table = "injection_rate";
//    protected $primaryKey = ['id'];

//    public $timestamps = false;
//    public $incrementing = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
