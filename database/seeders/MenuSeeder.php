<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'route_name' => 'dashboard.index',
                'icon' => 'fas fa-atom fs-3',
                'level' => '0',
                'sequence' => '0050',
                'action' => []
            ],
            [
                'name' => 'SP3',
                'route_name' => 'sp3.index',
                'icon' => 'fas fa-atom fs-3',
                'level' => '0',
                'sequence' => '0100',
                'action' => [
                    'create',
                    'view',
                    'edit',
                    'amandemen',
                    'approve1',
                    // 'approve2',
                    'print',
                ]
            ],
            [
                'name' => 'SPP',
                'route_name' => 'spp.index',
                'icon' => 'fas fa-atom fs-3',
                'level' => '0',
                'sequence' => '0200',
                'action' => [
                    'create',
                    'view',
                    'edit',
                    'amandemen',
                    'approve1',
                    'approve2',
                    'approve3',
                    'print',
                ]
            ],
            [
                'name' => 'SPM',
                'route_name' => 'spm.index',
                'icon' => 'fas fa-atom fs-3',
                'level' => '0',
                'sequence' => '0300',
                'action' => [
                    'create',
                    'konfirmasi',
                    'konfirmasi_vendor',
                    'print',
                    'edit',
                    'view',
                    'buat_sptb',
                    'armada_tiba'
                ]
            ],
            [
                'name' => 'SPTB',
                'route_name' => 'sptb.index',
                'icon' => 'fas fa-atom fs-3',
                'level' => '0',
                'sequence' => '0400',
                'action' => [
                    'create',
                    'view',
                    'edit',
                    'print',
                    'konfirmasi',
                    'penilaian_mutu',
                    'penilaian_pelayanan',
                ]
            ],
            [
                'name' => 'Pricelist Angkutan',
                'route_name' => 'pricelist-angkutan.index',
                'icon' => 'fas fa-atom fs-3',
                'level' => '0',
                'sequence' => '0500',
                'action' => [
                    'create',
                    'view',
                    'edit',
                    'delete',
                ]
            ],
            [
                'name' => 'Master',
                'route_name' => '#',
                'icon' => 'fas fa-atom fs-3',
                'level' => '1',
                'sequence' => '0600'
            ],
            [
                'name' => 'Driver',
                'route_name' => 'master-driver.index',
                'icon' => 'bi bi-card-list fs-3',
                'level' => '2',
                'sequence' => '0610'
            ],
            [
                'name' => 'Armada',
                'route_name' => 'master-armada.index',
                'icon' => 'bi bi-card-list fs-3',
                'level' => '2',
                'sequence' => '0620'
            ],
            [
                'name' => 'Verifikasi',
                'route_name' => '#',
                'icon' => 'fas fa-atom fs-3',
                'level' => '1',
                'sequence' => '0700'
            ],
            [
                'name' => 'Armada',
                'route_name' => 'verifikasi-armada.index',
                'icon' => 'bi bi-card-list fs-3',
                'level' => '2',
                'sequence' => '0710'
            ],
            [
                'name' => 'Potensi Detail Armada',
                'route_name' => 'potensi.detail.armada.index',
                'icon' => 'fas fa-atom fs-3',
                'level' => '0',
                'sequence' => '0800'
            ],
            [
                'name' => 'Kalender Pengiriman',
                'route_name' => 'kalender-pengiriman.index',
                'icon' => 'fas fa-atom fs-3',
                'level' => '0',
                'sequence' => '0900'
            ],
            [
                'name' => 'Report',
                'route_name' => '#',
                'icon' => 'fas fa-atom fs-3',
                'level' => '1',
                'sequence' => '1000'
            ],
            [
                'name' => 'Ra Ri Pemenuhan Armada',
                'route_name' => 'report-pemenuhan-armada.index',
                'icon' => 'bi bi-card-list fs-3',
                'level' => '2',
                'sequence' => '1010'
            ],
            [
                'name' => 'Monitoring Distribusi',
                'route_name' => 'report-monitoring-distribusi.index',
                'icon' => 'bi bi-card-list fs-3',
                'level' => '2',
                'sequence' => '1020'
            ],
            [
                'name' => 'Evaluasi Vendor',
                'route_name' => 'report-evaluasi-vendor.index',
                'icon' => 'bi bi-card-list fs-3',
                'level' => '2',
                'sequence' => '1030'
            ],
            [
                'name' => 'Setting',
                'route_name' => '#',
                'icon' => 'fas fa-atom fs-3',
                'level' => '1',
                'sequence' => '1100'
            ],
            [
                'name' => 'Akses Menu',
                'route_name' => 'setting.akses.menu.index',
                'icon' => 'bi bi-card-list fs-3',
                'level' => '2',
                'sequence' => '1110'
            ],
        ];

        $parent0 = null;
        $parent1 = null;
        $parent2 = null;
        foreach ($menus as $item) {
            $menu = Menu::firstOrNew([
                'seq' => $item['sequence']
            ]);
            $menu->name       = $item['name'];
            $menu->route_name = $item['route_name'];
            $menu->icon       = $item['icon'];
            $menu->level      = $item['level'];
            $menu->action     = $item['action'] ?? [];

            $menu->seq        = $item['sequence'];
            if(in_array($item['level'], [2, 3])){
                $menu->parent_id = $parent1;
            }
            if($item['level'] == 4){
                $menu->parent_id = $parent2;
            }
            $menu->save();

            if($menu->level == 1){
                $parent1 = $menu->id;
            }
            if($menu->level == 3){
                $parent2 = $menu->id;
            }
            $menu->save();
        }
    }
}
