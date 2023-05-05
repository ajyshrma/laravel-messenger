<?php

namespace Ajyshrma69\LaravelMessenger\Models;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Chat extends Model
{
    use HasFactory;

    const IS_READ = 1;
    const IS_NOT_READ = 0;
    const IS_GROUP_CHAT = 1;
    const IS_GROUP_ADMIN = 1;

    protected $fillable = [
        'uuid',
        'title',
        'is_group_chat',
        'updated_at'
    ];

    /**
     * @return \App\Models\User
     */

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \App\Models\User
     */

    public function getDisplayIcon()
    {
        $icon = '';
        $userCount = $this->users()->count();
        if ($userCount ==  2) {
            $user = $this->getUser();
            if ($user) {
                $icon = "<div class='avatar'><img src='" . getUserAvatar($user->name) . "'></div>";
            }
        } else {
            $users = $this->users()->limit(2)->get();
            $icon = '<div class="avatar-group">';

            foreach ($users as $user) {
                $icon .=   '<div class="avatar">
                            <img class="avatar-img rounded-circle border border-white" alt="User Image" src="' . getUserAvatar($user->name) . '">
                        </div>';
            }
            if ($userCount > 4) {
                $icon .=   '<div class="avatar"><img class="avatar-img rounded-circle border border-white" alt="User Image" src="' . getUserAvatar(($userCount - 2) . " +") . '"></div>';
            }
            $icon .= '</div>';
        }

        return $icon;
    }

    public function getTitle()
    {
        $title = $this->title;
        $userCount = $this->users()->count();
        if ($userCount <= 2) {
            $user = $this->getUser();
            $title = $user->name;
        }

        return $title;
    }

    /**
     * @return \App\Models\User
     */

    public function getUser()
    {
        $subQuery = DB::table('chat_user')->select('user_id')->where('user_id', '!=', auth()->id())
            ->where('chat_id', $this->id);
        $user = User::whereIn('id', $subQuery)->first();
        return $user;
    }

    /**
     * @return \Ajyshrma69\LaravelMessenger\Models\ChatMessage
     */

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id', 'id');
    }

    public function lastMessage()
    {
        return $this->messages()->orderByDesc('created_at')->limit(1)->first();
    }

    public function UnreadMessageCount()
    {
        return $this->messages()->where('user_id', '!=', auth()->id())->where('is_read', self::IS_NOT_READ)->count();
    }

    /**
     * @param int $user_id
     * @return int $id
     */

    public static function getChatId($user_id, $title = null)
    {
        $subQuery = ChatUser::select('chat_id')->where('user_id', auth()->id());
        $chatUser = ChatUser::where('user_id', $user_id)->wherehas('chat', function ($query) use ($subQuery) {
            return $query->where('is_group_chat', 0)->whereIn('id', $subQuery);
        })->first();
        if (!$chatUser) {
            DB::beginTransaction();
            try {
                $chat = Chat::create([
                    'uuid' => Str::random(12),
                    'title' => $title
                ]);
                self::addUserToChat($chat->id, auth()->id(), self::IS_GROUP_ADMIN);
                self::addUserToChat($chat->id, $user_id);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        } else {
            $chat = $chatUser->chat;
        }

        return $chat->id;
    }

    /**
     * Add a user to the chat
     * @return void
     */

    public static function addUserToChat($chat_id, $user, $is_admin = 0)
    {
        DB::table('chat_user')->insert([
            'user_id' => $user,
            'chat_id' => $chat_id,
            'is_admin' => $is_admin
        ]);
    }
}
