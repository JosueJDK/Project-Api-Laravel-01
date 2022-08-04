<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'id',
        'task_title',
        'task_description',
        'task_status',
        'user_id'
    ];
}
