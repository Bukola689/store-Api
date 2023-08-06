<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    const TOKEN_EXPIRES_IN_HOURS = 1;

    protected $guarded = [];

    public $timestamps = false;

    public static function createToken(string $email)
    {
        return self::create([
            'token' => Str::random(40),
            'email' => $email,
        ]);
    }

    public static function verifyToken($token): ? self
    {  
        $reset = self::firstWhere(['token' => $token]);

        Carbon::now()->addHour();
       
        return $reset;

    }    
}
