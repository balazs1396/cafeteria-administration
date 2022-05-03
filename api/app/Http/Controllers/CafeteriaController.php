<?php

namespace App\Http\Controllers;

use App\Http\Requests\CafeteriaRequest;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CafeteriaController extends Controller
{

    public function index(): JsonResponse
    {
        return new JsonResponse(Account::all());
    }

    /**
     * @param   CafeteriaRequest  $req
     *
     * @return JsonResponse
     */
    public function store(CafeteriaRequest $req): JsonResponse
    {
        if ( ! $cafeteria = Cafeteria::find(1)) {
            $cafeteria = Cafeteria::create([
                'start_month' => $req->start_month,
                'user_id'     => 1,
            ]);
        } else {
            Cafeteria::where('id', 1)->update(['start_month' => $req->start_month]);
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
