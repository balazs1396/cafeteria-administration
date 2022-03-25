<?php


namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    /** @var string */
    private static $userEmail = 'test@bluecolibriapp.com';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => self::$userEmail],
            [
                'name'     => 'Test user',
                'password' => bcrypt('test123'),
            ]
        );
    }

}
