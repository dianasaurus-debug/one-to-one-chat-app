<?php

namespace App\Http\Controllers;

use App\Events\SessionEvent;
use App\Http\Resources\SessionResource;
use App\Models\Sessions;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function create(Request $request){
        $session = Sessions::create(['user1_id'=> auth()->id(), 'user2_id' => $request->friend_id]);
        $modified_session = new SessionResource($session);
        broadcast(new SessionEvent($modified_session, auth()->id()));
        return $modified_session;
    }
}
