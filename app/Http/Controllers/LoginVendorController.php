<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Log;
use DB;

class LoginVendorController extends Controller
{
    public function index()
    {
        return view('pages.tms-vendor.auth.login');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'email'],
            'password' => ['required'],
        ]);

 		$validate = $this->validateUser($request);

 		if ($validate) {
	        // if (Auth::attempt($credentials)) {
            if (Auth::loginUsingId($validate->id)) {
	            $request->session()->regenerate();
	 
	            return redirect()->intended('vendor.testing');
	        }
 		} else {
            return back()->withErrors([
                'general' => 'The provided credentials do not match our records.',
            ])->onlyInput('general');
        }
    }

    private function validateUser(Request $request)
    {
    	try {
    		$data = DB::connection('oracle-eproc')
    			->table(DB::raw('"m_user"'))
    			->where('username', $request->username)
    			->first();
    		
    		if ($data) {
    			$user = User::updateOrCreate(
    				[
    					'username' => $data->username,
    					'password' => $data->password
    				],
    				[
    					'user_id' => $data->user_id,
    					'trader_id' => $data->trader_id,
    					'fullname' => $data->fullname,
    					'phone' => $data->phone,
    					'position' => $data->position
    				]
    			);

    			return $user;
    		} else {
    			return false;
    		}
    	} catch(Exception $e) {
    		log::info(_METHOD_." ".$e->getMessage());
    		return false;
    	}
    }

    public function signOut() {
        Session::flush();
        Auth::logout();
  
        return redirect()->route('vendor.login');
    }
}