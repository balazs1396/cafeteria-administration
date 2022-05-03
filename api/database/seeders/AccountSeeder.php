<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Enums\AccountEnum;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (AccountEnum::getValues() as $accountName) {
            Account::updateOrCreate([
                'name' => $accountName,
            ],
                [
                    'annual_value' => 0,
                    'start_month'  => 1,
                ]
            );
        }
    }

}
