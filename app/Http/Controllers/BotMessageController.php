<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotMessageStoreRequest;
use App\Http\Requests\BotMessageUpdateRequest;
use App\Models\BotMessage;
use Illuminate\Http\Request;

class BotMessageController extends Controller
{
    public function index()
    {
        $messages = BotMessage::orderBy('id')->paginate(20);
        return view('bot-messages', compact('messages'));
    }

    public function store(BotMessageStoreRequest $request)
    {
        $botMessage = BotMessage::create($request->validated());
        $botMessage->save();
        return redirect(route('bot_messages'));
    }

    public function edit(BotMessage $message)
    {
        return view('bot-message-edit', compact('message'));
    }

    public function update(BotMessageUpdateRequest $request, BotMessage $message)
    {
        $message->update($request->validated());
        return back();
    }

    public function delete(BotMessage $message)
    {
        $message->delete();
        return redirect(route('bot_messages'));
    }
}
