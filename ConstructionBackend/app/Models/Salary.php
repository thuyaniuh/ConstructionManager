<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $primaryKey = 'salary_id';
    protected $fillable = [
        'user_id',
        'time_entry',
        'rate',
        'total',
        'advance',
        'advance_date',
        'status',
        'remaining',
        'salary_month',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
