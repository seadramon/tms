<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\Menu;
use App\Models\MenuMobile;
use App\Models\RoleMenu;
use App\Models\RoleMenuMobile;
use Yajra\DataTables\Facades\DataTables;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function data(){
        $data = Role::with(['menus' => function($sql){
                $sql->orderBy('seq','ASC');
            }])
            // ->where('grpid', 'like', 'B%')
            ->where(function($sql){
                $sql->where('grpid', 'like', 'B%');
                $sql->orWhere('grpid', '#1');
            })
            ->select('*');

        return DataTables::of($data) 
            ->addColumn('menu_list',function ($item){
                $temp = '';
                foreach($item->menus as $row){
                    if($row->level == 1){
                        $temp .= '<b>#'.$row->name.'</b><br>';
                    }else{
                        $temp .= '--'.$row->name.'<br>';
                    }
                }
                return $temp;
                
            })
            ->addColumn('menu',function ($item){
                return 
                    '<a href="'.route('setting.akses.menu.setting', ['id' => $item->id]).'" class="btn btn-primary btn-sm me-1" title="setting">
                        setting
                    </a>';
            })
            ->rawColumns(['menu','menu_list'])
            ->make(true);
    }

    public function index()
    {
        return view('pages.setting.akses-menu.index');
    }

    public function tree_data(Request $request){
        $data = Menu::with(['in_role' => function($sql) use ($request){
                $sql->where('role_id',$request->id);
            }])->orderBy('seq','ASC')
            ->get();

        $json = [];
        foreach($data as $row){
            $icon = 'fa fa-link';
            if(in_array($row->level, [1, 3])){
                $icon = 'fa fa-folder';
            }else if($row->level == 2 || $row->level == 4 ){
                $icon = 'fa fa-file';
            }
            $json[] = [
                'id' => $row->id,
                'parent' => in_array($row->level, [2, 3, 4]) ? $row->parent_id : '#',
                'text' => $row->name,
                'icon' => $icon,
                'li_attr' => ['val_id'=>$row->id],
                'state' => [
                    'selected' => ($row->in_role == null) || in_array($row->level, [1, 3]) || (count($row->action) > 0)  ? false : true,
                    'opened' => true
                ]
            ];
            foreach ($row->action as $action) {
                $json[] = [
                    'id' => $row->id . "|" . $action, 
                    'parent' => $row->id,
                    'text' => Str::camel($action),
                    'icon' => 'fa-solid fa-location-crosshairs',
                    'li_attr' => ['val_id' => $row->id . "|" . $action],
                    'state' => [
                        'selected' => in_array($action, $row->in_role->action_menu ?? []) ? true : false,
                        'opened' => true
                    ]
                ];
            }
        }

        // $data = MenuMobile::with(['in_role' => function($sql) use ($request){
        //         $sql->where('role_id',$request->id);
        //     }])->get();
        // foreach($data as $row){
        //     $sel = false;
        //     if($row->in_role){
        //         $sel = true;
        //     }
        //     $json[] = [
        //         'id' => "mobile_" . $row->id,
        //         'parent' => '#',
        //         'text' => "[Mobile]" . $row->name,
        //         'icon' => 'fa fa-mobile',
        //         'li_attr' => ['val_id'=> "mobile" . $row->id],
        //         'state' => [
        //             'selected'=> $sel,
        //             'opened' => true
        //         ]
        //     ];
        // }

        return response()->json(['data' => $json]);
    }

    public function setting($id)
    {   
        $data = Menu::with(['in_role' => function($sql) use ($id){
                    $sql->where('role_id',$id);
                }])->orderBy('seq','ASC')
                ->get();
        
        return view('pages.setting.akses-menu.setting', ['id' => $id]);
    }

    public function update_setting(Request $request)
    {
        $id = $request->role_id;
        $data = collect($request->data)->filter(function($item){ return !str_contains($item, '|'); });
        $actions = collect($request->data)->filter(function($item){ return str_contains($item, '|'); })->groupBy(function($item){ return explode('|', $item)[0]; });
        // return response()->json($actions[1]->map(function($item){ return explode("|", $item)[1]; }));
        
        $clearing = RoleMenu::where('role_id',$id)->delete();
        // $clearing = RoleMenuMobile::where('role_id',$id)->delete();
       
        foreach($data as $row){
            if($row != null){
                if(str_contains($row, 'mobile')){
                    $menuid = str_replace('mobile', '', $row);
                    $rmm = new RoleMenuMobile;
                    $rmm->role_id = $id;
                    $rmm->menu_id =$menuid;
                    $rmm->save();
                }else{
                    $roleName = Role::find($id);
                    $cc = Menu::where('id',$row)->first(); 
    
                    if($cc->level == 0){
                        $input = new RoleMenu();
                        $input->role_id = $id;
                        $input->menu_id = $cc->id;
                        $input->save();
                    }else if($cc->level == 2){
                        $input = new RoleMenu();
                        $input->role_id = $id;
                        $input->menu_id = $row;
                        $input->save();
    
                        $aa = Menu::find($row);
                        $ch = RoleMenu::where('role_id',$id)->where('menu_id',$aa->parent_id)->first();
                        if($aa != null && $ch == null){
                            $input = new RoleMenu();
                            $input->role_id = $id;
                            $input->menu_id = $aa->parent_id;
                            $input->save();
                        }
                    }else if($cc->level == 4){
                        $input = new RoleMenu();
                        $input->role_id = $id;
                        $input->menu_id = $row;
                        $input->save();
    
                        $aa = Menu::find($row);
                        $ch = RoleMenu::where('role_id',$id)->where('menu_id',$aa->parent_id)->first();
                        if($aa != null && $ch == null){
                            $input = new RoleMenu();
                            $input->role_id = $id;
                            $input->menu_id = $aa->parent_id;
                            $input->save();
                        }
    
                        $aa1 = Menu::find($aa->parent_id);
                        $ch1 = RoleMenu::where('role_id',$id)->where('menu_id',$aa1->parent_id)->first();
                        if($aa1 != null && $ch1 == null){
                            $input = new RoleMenu();
                            $input->role_id = $id;
                            $input->menu_id = $aa1->parent_id;
                            $input->save();
                        }
                    }
                }
            }
        }
        foreach ($actions as $key => $rows) {
            $action_menu = collect($rows)->map(function($item){ return explode("|", $item)[1]; });
            $input = RoleMenu::firstOrNew([
                'role_id' => $id,
                'menu_id'  => $key
            ]);
            $input->action_menu = $action_menu->all();
            $input->save();
        }                       
        return 'success';
    }

    public function delete_setting($id, FlasherInterface $flasher)
    {
        $checking = RoleMenu::where('role_id',$id)->delete();
        $flasher->addSuccess('Data berhasil dihapus!');
        return redirect()->route('setting.akses.menu.setting', ['id' => $id]);
    }
}