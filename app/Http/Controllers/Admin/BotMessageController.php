<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotMessageStoreRequest;
use App\Http\Requests\BotMessageUpdateRequest;
use App\Models\BotMessage;

class BotMessageController extends Controller
{
    public function index()
    {
        $messages = BotMessage::orderBy('id')->paginate(20);
        return view('admin.bot-message-index', compact('messages'));
    }

    public function store(BotMessageStoreRequest $request)
    {
        $botMessage = BotMessage::create($request->validated());
        $botMessage->save();
        return redirect(route('bot_messages.index'));
    }

    public function edit(BotMessage $message)
    {
        return view('admin.bot-message-edit', compact('message'));
    }

    public function update(BotMessageUpdateRequest $request, BotMessage $message)
    {
        $message->update($request->validated());
        return back();
    }

    public function delete(BotMessage $message)
    {
        $message->delete();
        return redirect(route('bot_messages.index'));
    }
}
