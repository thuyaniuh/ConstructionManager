<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $primaryKey = 'project_id';
    protected $fillable = [
        'name',
        'type',
        'description',
        'start_day',
        'end_day',
        'status',
        'budget'
    ];
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'project_id');
    }

    public function entries()
    {
        return $this->hasMany(Entry::class, 'project_id');
    }
}
