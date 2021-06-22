<?php

namespace Database\Seeders;
use App\Models\領用部門;
use Illuminate\Database\Seeder;

class 領用部門TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "領用部門";

    public function run()
    {
        //
        $use = new 領用部門;
        $use->領用部門 = 'FATB';
        $use->save();
        //
        $use = new 領用部門;
        $use->領用部門 = '生產二課';
        $use->save();
        //
        $use = new 領用部門;
        $use->領用部門 = '物料一課';
        $use->save();
        //
        $use = new 領用部門;
        $use->領用部門 = '測試課';
        $use->save();
    }
}
