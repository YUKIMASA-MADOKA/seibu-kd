<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PossData extends Model
{
    use HasFactory;
    protected $table = "view_kd";
    protected $primaryKey = ['ymh'];

    public $timestamps = false;
    public $incrementing = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
