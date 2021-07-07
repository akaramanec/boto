<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotSetting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotSettingController extends Controller
{
    public function index()
    {
        return view('admin.bot-settings-index', BotSetting::getSettings());
    }

    public function store(Request $request)
    {
        BotSetting::where('key', '!=', null)->delete();
        foreach ($request->except('_token') as $key => $value) {
            $botSetting = new BotSetting();
            $botSetting->key = $key;
            $botSetting->value = $request->$key;
            $botSetting->save();
        }
        return redirect(route('bot_settings.index'));
    }

    public function setWebhook(Request $request)
    {
        $result = $this->sendTelegramData('setwebhook', ['url' => $request->url . "/" . Telegram::getAccessToken()]);
        return redirect(route('bot_settings.index'))->with('status', $result);
    }

    public function getWebhookInfo(Request $request)
    {
        $result = $this->sendTelegramData('getWebhookInfo');
        return redirect(route('bot_settings.index'))->with('status', $result);
    }

    public function sendTelegramData($uri = '', $params = [], $method = 'post')
    {
        $client = new Client(['base_uri' => 'https://api.telegram.org/bot' . Telegram::getAccessToken() . '/']);
        $result = $client->request($method, $uri, $params);
        return (string)$result->getBody();
    }

    public function test()
    {
        dd(Telegram::getAccessToken());
    }
}
