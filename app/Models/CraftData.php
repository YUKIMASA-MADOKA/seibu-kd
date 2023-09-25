<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CraftData extends Model
{
    use HasFactory;
    protected $table = "craftman_rate";
//    protected $primaryKey = ['id'];

//    public $timestamps = false;
//    public $incrementing = false;

    protected $fillable = ['day','hms','injection_rate','day_hms'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
