<?php

namespace Ajyshrma69\LaravelMessenger\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ChatMessage extends Model
{
    use HasFactory;

    const IS_READ = 1;
    const IS_NOT_READ = 0;

    public static function boot()
    {
        parent::boot();
        static::created(function (ChatMessage $model) {
            $chat  = $model->chat;
            $chat->updated_at = now();
            $chat->save();
        });
    }
    /**
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \App\Models\User
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return $chat
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
