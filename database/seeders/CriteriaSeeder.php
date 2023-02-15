<?php

namespace Database\Seeders;

use App\Models\ArmadaCriteria;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $data = [
            [
                'code' => 'sim',
                'criteria' => 'SIM B2/B2 umum',
                'description' => 'SIM B2/B2 Umum yang masih berlaku',
                'bobot' => '6'
            ],
            [
                'code' => 'usia',
                'criteria' => 'Usia driver',
                'description' => 'Usia min. 23 tahun sesuai persyaratan perundangan',
                'bobot' => '6'
            ],
            [
                'code' => 'kesehatan',
                'criteria' => 'Pemeriksaan Kesehatan',
                'description' => 'Driver dalam kondisi sehat, tidak dalam pengaruh alkohol dan obat-obatan terlarang',
                'bobot' => '6'
            ],
            [
                'code' => 'apd',
                'criteria' => 'Driver menggunakan alat pelindung diri (APD)',
                'description' => 'Driver menggunakan baju kerja/rompi/vest, helm & safety shoes',
                'bobot' => '8'
            ],
            [
                'code' => 'mesin',
                'criteria' => 'Mesin kendaraan berfungsi baik dan normal',
                'description' => 'Mesin terlihat baik secara audio visual dan tidak melakukan storing/perbaikan berat di lokasi muat',
                'bobot' => '12'
            ],
            [
                'code' => 'pengereman',
                'criteria' => 'Pengereman berfungsi baik dan normal',
                'description' => 'Pengereman tidak mengalami kendala saat kendaraan berhenti maupun saat mengurangi kecepatan',
                'bobot' => '12'
            ],
            [
                'code' => 'sling_belt_jumlah',
                'criteria' => 'Jumlah sling belt/rantai pengikat',
                'description' => 'Jumlah sling belt/rantai pengikat sesuai manual produk',
                'bobot' => '5'
            ],
            [
                'code' => 'sling_belt_material',
                'criteria' => 'Material sling belt/rantai pengikat, hook, jarum keras/tracker belt',
                'description' => 'Material sling belt/rantai pengikat, hook, jarum keras/tracker belt memiliki dimensi yang sesuai dan dalam kondisi layak',
                'bobot' => '5'
            ],
            [
                'code' => 'sling_belt_safety',
                'criteria' => 'Pengaman ikatan rantai',
                'description' => 'Mempunyai sling belt / rantai, stoper (kayu baji/ baja hollow) material dan ban',
                'bobot' => '6'
            ],
            [
                'code' => 'kayu_ganjal',
                'criteria' => 'Kayu ganjal',
                'description' => 'Kayu ganjal memenuhi standar kualitas sesuai manual produk (jumlah sesuai dan material dari kayu keras)',
                'bobot' => '5'
            ],
            [
                'code' => 'safety',
                'criteria' => 'Peralatan safety pada kendaraan',
                'description' => 'Tersedia alat pemadam api ringan (APAR), kotak P3K, stopper ban, ban serep/cadangan, safety cone/segitiga safety, dan peralatan safety lainnya',
                'bobot' => '5'
            ],
            [
                'code' => 'rotator',
                'criteria' => 'Lampu rotator',
                'description' => 'Terdapat lampu rotator yang berfungsi normal',
                'bobot' => '5'
            ],
            [
                'code' => 'handling',
                'criteria' => 'Handling produk',
                'description' => 'Metode penumpukan dan pengikatan sesuai manual produk',
                'bobot' => '8'
            ],
            [
                'code' => 'label',
                'criteria' => 'Label/sticker/media informasi untuk pengemudi lain',
                'description' => 'Terdapat label yang jelas untuk bahan kimia, muatan alat berat, dan merk armada',
                'bobot' => '5'
            ],
            [
                'code' => 'identitas',
                'criteria' => 'Identitas Armada',
                'description' => 'Terdapat sticker nama/logo vendor dan nama/logo wika beton pada armada',
                'bobot' => '6'
            ]
        ];

        foreach ($data as $item) {
            $temp = ArmadaCriteria::firstOrNew([
                'code' => $item['code']
            ]);
            $temp->criteria    = $item['criteria'];
            $temp->description = $item['description'];
            $temp->bobot       = $item['bobot'];
            $temp->save();
        }
    }
}
