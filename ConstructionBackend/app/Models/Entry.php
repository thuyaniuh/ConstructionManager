<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Entry extends Model
{
    use HasFactory;

    protected $primaryKey = 'entry_id'; // Sử dụng cột entry_id làm khóa chính

    protected $fillable = [
        'project_id',
        'user_id',
        'start_time',
        'end_time',
        'hour',
        'note',
    ];

    // Quan hệ với bảng projects
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Quan hệ với bảng users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Tính toán số giờ làm việc khi có start_time và end_time
    public function getHourAttribute()
    {
        if ($this->start_time && $this->end_time) {
            $start = Carbon::parse($this->start_time);
            $end = Carbon::parse($this->end_time);
            return $end->diffInHours($start);
        }

        return null; // Nếu chưa có end_time thì trả về null
    }

    // Mutator để tính số giờ làm việc khi end_time được đặt
    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = $value;

        if ($this->start_time && $value) {
            $start = Carbon::parse($this->start_time);
            $end = Carbon::parse($value);
            $this->attributes['hour'] = $end->diffInHours($start); // Tự động tính giờ làm việc
        }
    }
}
