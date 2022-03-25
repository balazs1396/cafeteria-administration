<?php

namespace App\Http\Controllers;

use App\Http\Requests\CafeteriaRequest;
use App\Models\Cafeteria;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CafeteriaController extends Controller
{

    public function store(CafeteriaRequest $req): JsonResponse
    {
        if ( ! $cafeteria = Cafeteria::find(1)) {
            $cafeteria = Cafeteria::create([
                'start_month' => $req->start_month,
                'user_id'     => 1,
            ]);
        }

        $sync = [];
        collect($req->accounts)->map(function ($account) use (&$sync) {
            $sync[Account::where('name', $account['name'])->firstOrFail()->id] = ['annual_value' => $account['annualValue']];
        });

        $cafeteria->accounts()->sync($sync);

        return new JsonResponse([
            'message' => 'Successful creation',
        ]);
    }

}
