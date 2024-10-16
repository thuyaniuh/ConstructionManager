<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $primaryKey = 'supplier_id';
    protected $fillable = [
        'supplier_name',
        'address',
        'phone',
    ];
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }
}
