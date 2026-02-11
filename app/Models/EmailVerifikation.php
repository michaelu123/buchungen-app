<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerifikation extends Model
{
    protected $table = "email_verifikationen";

    protected $fillable = [
        'email',
        'verified',
    ];


    public static function verifyEmail($email)
    {
        $now = now();
        if (!EmailVerifikation::where('email', $email)->exists()) {
            EmailVerifikation::create(['email' => $email, 'verified' => $now]);
        } else {
            EmailVerifikation::where('email', $email)->update(['verified' => $now]);
        }
        \App\Models\Technik\Buchung::verifyEmail($email, $now);
    }
}
