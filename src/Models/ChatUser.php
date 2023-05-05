<?php

namespace Ajyshrma69\LaravelMessenger\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\User;

class ChatUser extends Pivot
{
    use HasFactory;

    /**
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Ajyshrma69\LaravelMessenger\Models\Chat
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
