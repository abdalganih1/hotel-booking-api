<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // كما هو في مخطط ERD

    protected $fillable = [
        'user_id',
        'question',
        'answer',
    ];

    // العلاقة
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}