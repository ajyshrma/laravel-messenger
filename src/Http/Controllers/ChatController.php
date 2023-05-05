<?php

namespace Ajyshrma69\LaravelMessenger\Http\Controllers;

use Ajyshrma69\LaravelMessenger\Models\Chat;
use Ajyshrma69\LaravelMessenger\Models\ChatMessage;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $layout = 'layouts.admin';

    public function __construct()
    {
        view()->share('layout', $this->layout);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active_chat_id = null;
        $users = $this->getAvailableChatUsers();
        $chats = $this->getChatList();
        return view('laravel-messenger::index', compact('users', 'active_chat_id', 'chats'));
    }



    public function loadMessages(Request $request)
    {
        $output = getErrorResponse();
        $chat = Chat::where('id', $request->chat_id)->first();
        $response = getErrorResponse();
        if ($chat) {
            $messages = $chat->messages;
            $chat->messages()->whereNotIn('user_id', [auth()->id()])
                ->update([
                    'is_read' => ChatMessage::IS_READ
                ]);
            $html = view('laravel-messenger::partials.templates.chat-right', compact('chat', 'messages'))->render();
            $output['success'] = true;
            $output['html'] = $html;
        }
        return response()->json($output);
    }


    public function createNewChat(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);
        $response = getErrorResponse();
        $chat_id = Chat::getChatId($request->user_id);
        $message = new ChatMessage();
        $message->chat_id = $chat_id;
        $message->user_id = auth()->id();
        $message->message = nl2br($request->message);

        if ($message->save()) {
            $active_chat_id = null;
            $chats = $this->getChatList();
            $response = [
                'success' => true,
                'html' => view('laravel-messenger::partials.templates.chat-left', compact('chats', 'active_chat_id'))->render()
            ];
        }

        return response()->json($response);
    }


    public function getChatList()
    {
        $chats = Chat::whereHas('users', function ($query) {
            return $query->where('user_id', auth()->id());
        })->orderByDesc('updated_at')->get();

        return $chats;
    }

    /**
     * Return list of all the list available to chat
     *
     * @return collection $users
     */

    public function getAvailableChatUsers()
    {
        $users = User::whereNotIn('id', [auth()->id()])->get();

        return $users;
    }
}
