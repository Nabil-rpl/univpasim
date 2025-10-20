<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'nama',
        'email',
        'nim',
        'jurusan',
    ];

    // Relasi one-to-one dengan User
    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }
}