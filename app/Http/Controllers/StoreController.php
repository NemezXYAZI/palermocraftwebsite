<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    private function products()
    {
        return [
            [
                'id' => 'vip_30',
                'name' => 'VIP Статус 30 днів',
                'description' => 'Доступ до VIP-команд, бонусів та пріоритету на сервері.',
                'price' => 500,
                'image' => 'images/store/vip.jpg',
                'type' => 'privilege',
            ],
            [
                'id' => 'premium_30',
                'name' => 'Premium Статус 30 днів',
                'description' => 'Розширені можливості, косметика та преміальні бонуси.',
                'price' => 900,
                'image' => 'images/store/pngwing.png',
                'type' => 'privilege',
            ],
            [
                'id' => 'resources_pack',
                'name' => 'Набір ресурсів',
                'description' => 'Корисний стартовий набір для виживання на сервері.',
                'price' => 250,
                'image' => 'images/store/resources.webp',
                'type' => 'item',
            ],
            [
                'id' => 'keys_5',
                'name' => '5 Ключів кейсів',
                'description' => 'Відкривай кейси та отримуй випадкові нагороди.',
                'price' => 350,
                'image' => 'images/store/keys.webp',
                'type' => 'keys',
            ],
            [
                'id' => 'coins_1000',
                'name' => '1000 Ігрових монет',
                'description' => 'Внутрішньоігрова валюта для економіки сервера.',
                'price' => 300,
                'image' => 'images/store/coins.webp',
                'type' => 'currency',
            ],
            [
                'id' => 'legend_prefix',
                'name' => 'Префікс Legend',
                'description' => 'Унікальний неоновий префікс у чаті сервера.',
                'price' => 700,
                'image' => 'images/store/prefix.webp',
                'type' => 'cosmetic',
            ],
        ];
    }

    private function servers()
    {
        return [
            'vanilla' => 'Ваніла+',
            'survival' => 'Survival',
            'anarchy' => 'Anarchy',
        ];
    }

    public function index()
    {
        return view('store.index', [
            'products' => $this->products(),
            'servers' => $this->servers(),
        ]);
    }

    public function buyNow(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'string'],
            'server' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'purchase_mode' => ['required', 'string', 'in:inventory,activate'],
        ]);

        $product = $this->findProduct($data['product_id']);

        if (!$product) {
            return back()->withErrors([
                'product' => 'Товар не знайдено.',
            ]);
        }

        $servers = $this->servers();

        if (!isset($servers[$data['server']])) {
            return back()->withErrors([
                'server' => 'Сервер не знайдено.',
            ]);
        }

        $user = $request->user();
        $total = $product['price'] * $data['quantity'];

        if ($user->balance < $total) {
            return back()->withErrors([
                'balance' => 'Недостатньо коштів на балансі.',
            ]);
        }

        DB::transaction(function () use ($user, $product, $data, $servers, $total) {
            $user->decrement('balance', $total);

            for ($i = 1; $i <= $data['quantity']; $i++) {
                if ($data['purchase_mode'] === 'activate') {
                    $user->inventoryItems()->create([
                        'name' => $product['name'],
                        'description' => 'Сервер: ' . $servers[$data['server']] . '. Товар активовано та видано на сервер.',
                        'price' => $product['price'],
                        'status' => 'activated',
                        'purchased_at' => now(),
                    ]);
                } else {
                    $user->inventoryItems()->create([
                        'name' => $product['name'],
                        'description' => 'Сервер: ' . $servers[$data['server']] . '. Товар очікує активації в інвентарі.',
                        'price' => $product['price'],
                        'status' => 'active',
                        'purchased_at' => now(),
                    ]);
                }
            }
        });

        if ($data['purchase_mode'] === 'activate') {
            return redirect()
                ->route('profile.index')
                ->with('success', 'Покупку оплачено. Товар активовано на сервері ' . $servers[$data['server']] . '.');
        }

        return redirect()
            ->route('profile.index')
            ->with('success', 'Покупку оплачено. Товар додано в інвентар.');
    }


    private function findProduct($productId)
    {
        foreach ($this->products() as $product) {
            if ($product['id'] === $productId) {
                return $product;
            }
        }

        return null;
    }
}
