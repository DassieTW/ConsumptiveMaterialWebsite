<?php

namespace Database\Seeders;
use App\Models\MPS;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MPSTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $table = "MPS";

    public function run()
    {
        //
        $mps = new MPS;
        $mps->客戶別 = 'Fendi';
        $mps->機種 = 'Atlas';
        $mps->製程 = 'FATP';
        $mps->下月MPS = 350000;
        $mps->下月生產天數 = 27;
        $mps->本月MPS = 200000;
        $mps->本月生產天數 = 12;
        $mps->填寫時間 = Carbon::create(2021, 3, 23, 14, 9, 3, 'GMT');
        $mps->save();
        //
        $mps = new MPS;
        $mps->客戶別 = 'Fendi';
        $mps->機種 = 'NMN';
        $mps->製程 = '無塵室';
        $mps->下月MPS = 45;
        $mps->下月生產天數 = 54;
        $mps->本月MPS = 54;
        $mps->本月生產天數 = 6;
        $mps->填寫時間 = Carbon::create(2021, 1, 27, 17, 6, 52, 'GMT');
        $mps->save();
        //
        $mps = new MPS;
        $mps->客戶別 = 'Cisco';
        $mps->機種 = 'EZ1K';
        $mps->製程 = 'FATP';
        $mps->下月MPS = 15000;
        $mps->下月生產天數 = 24;
        $mps->本月MPS = 20000;
        $mps->本月生產天數 = 26;
        $mps->填寫時間 = Carbon::create(2021, 3, 25, 9, 42, 1, 'GMT');
        $mps->save();
    }
}
