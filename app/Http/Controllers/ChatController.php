<?php

namespace App\Http\Controllers;

use App\Events\PrivateChatEvent;
use App\Events\ReadEvent;
use App\Http\Resources\ChatResource;
use App\Models\Sessions;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $casts = [
        'read_at' => 'datetime',
    ];
    public function send(Sessions $session, Request $request){
        $message = $session->messages()->create(['content' => $request->isi]);
        $chat = $message->createForSend($session->id);
        $message->createForReceived($session->id, $request->to_user);
        broadcast(new PrivateChatEvent($message->content, $chat));
        return response($chat->id, 200);

    }
    public function chats(Sessions $session){
        return ChatResource::collection($session->chats->where('user_id', auth()->id()));
    }
    public function read(Sessions $session){
        $chats = $session->chats->where('read_at', null)->where('type', 0)->where('user_id', '!=', auth()->id());
        foreach ($chats as $chat){
            $chat->update(['read_at' => Carbon::now()]);
            broadcast(new ReadEvent(new ChatResource($chat), $chat->session_id));
        }
    }
    public function clear(Sessions $session){
        $session->deleteChats();
        $session->chats()->count() == 0 ? $session->deleteMessages() : "";
        return response('cleared', 200);
    }
}
