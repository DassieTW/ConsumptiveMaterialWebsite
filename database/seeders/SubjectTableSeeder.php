<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models;

class SubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subject = new Models\Subject;
        $subject->name = '測試主題';
        $subject->save();

        $subject = new Models\Subject;
        $subject->name = '測試主題2';
        $subject->save();
    }
}
