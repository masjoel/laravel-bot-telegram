<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotTelegramController extends Controller
{
    function setWebhook()
    {
        $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
        dd($response);
    }

    public function commandHandlerWebHook()
    {
        $updates = Telegram::commandsHandler(true);
        $chat_id = $updates->getChat()->getId();
        $username = $updates->getChat()->getFirstName();

        $cek = Wisata::where('nama_wisata', 'like', '%' . $updates->getMessage()->getText() . '%')->first();

        if (strtolower($updates->getMessage()->getText() === 'halo')) {
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Halo ' . $username
            ]);
        } elseif ($cek == null) {
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Wisata tidak tersedia'
            ]);
        } else {
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $cek->informasi
            ]);
        }
    }
}
