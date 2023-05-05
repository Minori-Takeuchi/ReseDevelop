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
            'id' => 'kT5sl5QZ4FS4XFvYb27LxgSuLS03',
            'name' => '管理者',
            'email' => 'admin@gest.com',
            'role' => 3,
        ];
        User::create($param);
        $param = [
            'id' => 'ySbOMTep1fQTTJ60JjImbDkrhPY2',
            'name' => '店舗代表者',
            'email' => 'manager@gest.manager.com',
            'role' => 2,
        ];
        User::create($param);
        $param = [
            'id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'name' => '利用者1',
            'email' => 'gest@gest.com',
            'role' => 1,
        ];
        User::create($param);
        $param = [
            'id' => 'E1YdKZ4jM9PDctkJOCCwZN62IqN2',
            'name' => '利用者2',
            'email' => 'gest2@gest.com',
            'role' => 1,
        ];
        User::create($param);
    }
}
