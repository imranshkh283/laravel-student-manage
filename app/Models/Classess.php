<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Students;

class Classess extends Model
{

    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['class_name', 'division'];

    public function students()
    {
        return $this->hasMany(Students::class, 'class_id');
    }

    public static function getNameAndDivision()
    {
        return self::select('id', 'class_name', 'division')->get();
    }
}
