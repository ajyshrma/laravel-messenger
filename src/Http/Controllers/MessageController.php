<?php

namespace Ajyshrma69\LaravelMessenger\Http\Controllers;

use Ajyshrma69\LaravelMessenger\Models\Chat;
use Ajyshrma69\LaravelMessenger\Models\ChatMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MessageController extends Controller
{


    public function send(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|integer'
        ]);
        $html = "";
        $response = getErrorResponse();
        if ($request->message != '' || $request->hasFile('attachment')) {
            $chat = Chat::find($request->chat_id);
            $message = new ChatMessage();
            $message->message = nl2br($request->message);
            $message->is_read = Chat::IS_NOT_READ;
            $message->chat_id = $request->chat_id;
            if ($request->file('attachment')) {
                $message->mime_type = $request->attachment->getMimeType();
                $fileName = Str::random(16) . '.' . $request->attachment->getClientOriginalExtension();
                $request->attachment->move(public_path('uploads/chats/attachment/'), $fileName);
                $message->attachment = 'uploads/chats/attachment/' . $fileName;
            }

            $message->user_id = auth()->id();
            if ($message->save()) {
                $html = view('laravel-messenger::partials.templates.message', compact('message'))->render();
                $response = ['success' => true, 'html' => $html];
            }
        }
        return response()->json($response);
    }


    public function fetchNewMessages(Request $request)
    {
        $chat = Chat::find($request->chat_id);
        $html = '';
        $sendUnreadMesssages = 0;
        if ($chat) {
            $subQuery = DB::table('chat_message_user')
                ->where('user_id', auth()->id())->select('chat_message_id');
            $messages = $chat->messages()->where('user_id', '!=', auth()->id())
                ->whereNotIn('id', $subQuery)->get();
            foreach ($messages as $message) {
                $html .= view('laravel-messenger::partials.templates.message', compact('message'))->render();
                $message->is_read = Chat::IS_READ;
                $message->save();
            }

            $sendUnreadMesssages = ChatMessage::where([
                'chat_id' => $request->chat_id,
                'is_read' => 0,
                'user_id' => auth()->id()
            ])->count();
        }
        return response()->json([
            'success' => true,
            'html' => $html,
            'unreadMessages' => $sendUnreadMesssages
        ]);
    }


    public function downloadAttachment($id)
    {
        $message = ChatMessage::find($id);
        if ($message) {
            if ($message->attachment) {
                $file = public_path($message->attachment);
                if (file_exists($file)) {
                    return response()->download($file);
                }
            }
        }
        abort('404', 'File you are looking for doesnot exists any more');
    }
}
