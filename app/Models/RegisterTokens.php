<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RegisterTokens extends Model
{
    use HasFactory;

    protected $table = 'register_tokens';

    protected $fillable = [
        "created_by",
        "token",
        "email",
        "nombre",
        "status"
    ];

    public function creador(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
