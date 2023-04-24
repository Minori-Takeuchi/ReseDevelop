<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'id' => 'lO0IDo5g65eJyM5GodUANmlitQs2',
            'name' => '管理者',
            'email' => 'admin@gest.com',
            'role' => 3,
        ];
        User::create($param);
        $param = [
            'id' => '15O7exnOGNTe4BcxuZsadKcXBGk1',
            'name' => '店舗代表者',
            'email' => 'manager@gest.com',
            'role' => 2,
        ];
        User::create($param);
        $param = [
            'id' => 'OHDQrOCGleXcGIaTKhLKSQzfNQG3',
            'name' => '利用者1',
            'email' => 'gest@gest.com',
            'role' => 1,
        ];
        User::create($param);
        $param = [
            'id' => 'ytRKpnzkxuaYwtgp3TTybTLseyy2',
            'name' => '利用者2',
            'email' => 'gest2@gest.com',
            'role' => 1,
        ];
        User::create($param);
    }
}
