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
        $startMonth = $req->start_month;
        collect($req->accounts)->map(function ($account) use ($startMonth) {
            Account::updateOrCreate(
                    ['name' => $account['name']],
                    ['start_month' => $startMonth, 'annual_value' => $account['annualValue']]
                );
        });

        return new JsonResponse([
            'message' => 'Saved',
        ]);
    }

}
