<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 必要に応じて変更してください。テストではUsersTableSeederのみ使用するため、シーディングを行った後は$this->call(UsersTableSeeder::class);のみにしておくことをお勧めします。
        $this->call(UsersTableSeeder::class);
        // $this->call(AreasTableSeeder::class);
        // $this->call(GenresTableSeeder::class);
        // $this->call(ShopsTableSeeder::class);
        // $this->call(CoursesTableSeeder::class);

    }
}
