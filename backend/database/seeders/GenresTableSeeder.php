<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'genre' => '寿司',
        ];
        Genre::create($param);
        $param = [
            'genre' => '焼肉',
        ];
        Genre::create($param);
        $param = [
            'genre' => '居酒屋',
        ];
        Genre::create($param);
        $param = [
            'genre' => 'イタリアン',
        ];
        Genre::create($param);
        $param = [
            'genre' => 'ラーメン',
        ];
        Genre::create($param);
    }
}
