<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageNotification;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function sendMessage(Request $request) {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'to_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                ], 422);
        }

        // Create the message
        $message = new Message([
            'from_id' => Auth::id(),
            'to_id' => $request->input('to_id'),
            'message' => $request->input('message'),
        ]);

        $chat_id = Message::where(function ($query) use ($message) {
                        $query->where('from_id', $message->from_id)
                            ->where('to_id', $message->to_id);
                    })->orWhere(function ($query) use ($message) {
                        $query->where('from_id', $message->to_id)
                            ->where('to_id', $message->from_id);
                    })->value('chat_id');
        if (!$chat_id) {
            $chat = new Chat();
            $chat_id = $chat->id;
        }
        $message->chat_id = $chat_id;
        $message->save();

        $recipient = $message->recipient();
        $recipient->notify(new MessageNotification($message));

        // Broadcast the message
        broadcast(new MessageSent($message));

        return response()->json([
            'message' => 'Message sent.',
        ]);
    }

    public function getConversation(Request $request) {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'to_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get the messages for the conversation between the authenticated user and the specified recipient
        $to_id = $request->input('to_id');
        $messages = Message::where(function ($query) use ($to_id) {
            $query->where('from_id', Auth::id())
                ->where('to_id', $to_id);
        })->orWhere(function ($query) use ($to_id) {
            $query->where('from_id', $to_id)
                ->where('to_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        return response()->json([
            'messages' => $messages
        ]);
    }
}
