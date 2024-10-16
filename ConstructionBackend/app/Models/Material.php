<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $primaryKey = 'material_id';
    protected $fillable = [
        'material_name',
        'unit',
        'price',
    ];
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'material_id');
    }
}
