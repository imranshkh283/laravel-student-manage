<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classess as ClassModel;

class Students extends Model
{
    protected $table = 'students';

    protected $fillable = ['name', 'class_id', 'roll_number'];

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function getFirstNameAttribute()
    {
        return explode(' ', $this->name)[0] ?? '';
    }

    public function getLastNameAttribute()
    {
        $parts = explode(' ', $this->name);
        array_shift($parts);
        return implode(' ', $parts);
    }
}
