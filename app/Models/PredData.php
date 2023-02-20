<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredData extends Model
{
    use HasFactory;
    protected $table = "future_data";
    protected $primaryKey = ['targetDate','hour'];

    public $timestamps = false;
    public $incrementing = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
