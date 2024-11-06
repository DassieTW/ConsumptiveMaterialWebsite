<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;


class CleanupBarcodeImg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barcodeimg:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清理條碼圖片';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->warn("Now Running [barcodeimg:clear]");
        try {
            \Log::channel('dbquerys')->info('---------------------------清理條碼圖片開始--------------------------');

            $dir = storage_path('app/public/barcodeImg');
            $except_files = array('.gitignore');
            foreach (glob("$dir/*") as $file) {
                if (!in_array(basename($file), $except_files)) {
                    unlink($file);
                } // if
            } // foreach

            \Log::channel('dbquerys')->info('---------------------------清理條碼圖片結束--------------------------');
            $this->info("[barcodeimg:clear] Command executed successfully!");
        } catch (Exception $e) {
            $this->error("Barcode Images CleanUp Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    }
}
