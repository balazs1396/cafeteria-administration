<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{

    /** @var string[] */
    private static $details = [
        'accommodation',
        'hospitality',
        'leisure',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$details as $accountName) {
            Account::updateOrCreate([
                'name' => $accountName,
            ]);
        }
    }

}
