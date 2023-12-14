<?php

namespace Database\Seeders;

use App\Models\Armada;
use App\Models\Group;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $armadas = Armada::all();
        $category = ['stnk', 'kir_head', 'kir_trailer', 'pajak'];
        foreach ($armadas as $armada) {
            $dir = 'vendor/' . $armada->vendor_id . '/' . 'armada/' . $armada->id;
            foreach ($category as $cat) {
                $name = "foto_" . $cat;
                if(Storage::has($dir . '/' . $cat . '.jpg')){
                    $armada->$name = $dir . '/' . $cat . '.jpg';
                }
                if(Storage::has($dir . '/' . $cat . '.jpeg')){
                    $armada->$name = $dir . '/' . $cat . '.jpeg';
                }
                if(Storage::has($dir . '/' . $cat . '.png')){
                    $armada->$name = $dir . '/' . $cat . '.png';
                }
                if(Storage::has($dir . '/' . $cat . '.pdf')){
                    $armada->$name = $dir . '/' . $cat . '.pdf';
                }
            }
            $armada->save();
        }
    }
}
