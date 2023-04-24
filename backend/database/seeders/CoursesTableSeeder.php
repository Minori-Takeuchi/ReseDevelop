<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            $param = [
                'shop_id' => $i,
                'course' => '席のみ',
                'price' => 0,
            ];
            Course::create($param);
        }

        for ($i = 1; $i <= 20; $i++) {
            $param = [
                'shop_id' => $i,
                'course' => '満足コース',
                'price' => 5000,
            ];
            Course::create($param);
        }
    }
}
