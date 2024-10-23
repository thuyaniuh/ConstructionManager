<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAssignment extends Model
{
    use HasFactory;

    protected $primaryKey = 'assignment_id';

    protected $fillable = [
        'project_id',
        'user_id',
        'assigned_at',
    ];

    // Quan hệ với bảng users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ với bảng projects
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
