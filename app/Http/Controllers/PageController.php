<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Skin;
use App\Models\User;
use App\Services\ServerStatus;
use App\Support\Content;

/** Публічні сторінки сайту. Тексти — у resources/content. */
class PageController extends Controller
{
    public function home(ServerStatus $serverStatus)
    {
        $status = $serverStatus->get();

        // Голови гравців онлайн: лише ті, у кого є схвалений скін на сайті (інакше — літера ніку)
        $heads = [];
        if ($status['online'] && $status['list'] !== []) {
            $withSkins = User::query()
                ->whereIn('minecraft_name', $status['list'])
                ->whereHas('skin', fn ($q) => $q->where('status', Skin::STATUS_APPROVED))
                ->pluck('minecraft_name');
            foreach ($withSkins as $name) {
                $heads[$name] = route('skins.png', $name);
            }
        }

        return view('pages.home', [
            'season' => Content::get('season'),
            'status' => $status,
            'heads' => $heads,
            'news' => News::published()->latest('published_at')->limit(3)->get(),
        ]);
    }

    public function world()
    {
        return view('pages.world', ['season' => Content::get('season')]);
    }

    public function guide()
    {
        return view('pages.guide', ['season' => Content::get('season')]);
    }

    public function rules()
    {
        return view('pages.rules', ['rules' => Content::get('rules')]);
    }

    public function support()
    {
        return view('pages.support');
    }
}
