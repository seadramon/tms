<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginVendorController extends Controller
{
    public function index()
    {
		// return session()->exists('TMP_WBSESSID');
        return view('pages.tms-vendor.auth.login1');
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
	 
	            return redirect()->to('/');
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
    			->where('password', md5($request->username . $request->password))
    			->first();
    		
    		if ($data) {
				$trader = DB::connection('oracle-eproc')
					->table(DB::raw('"m_trader"'))
					->where('trader_id', $data->trader_id)
					->first();
    			$user = User::firstOrNew(
    				[
    					'username' => $data->username,
    					// 'password' => $data->password
    				]);
				
				$user->password = $data->password;
				$user->user_id = $data->user_id;
				$user->trader_id = $data->trader_id;
				$user->vendor_id = $trader->vendor_id ?? null;
				$user->name = $trader->pimpinan_nama ?? null;
				$user->fullname = $data->fullname;
				$user->phone = $data->phone;
				$user->position = $trader->pimpinan_jabatan ?? null;
				$user->save();

    			return $user;
    		} else {
    			return false;
    		}
    	} catch(Exception $e) {
    		Log::info($e->getMessage());
    		return false;
    	}
    }

    public function signOut() {
        Session::flush();
		Session::forget('TMP_WBSESSID');
        Auth::logout();
  
        return redirect()->route('vendor.login');
    }
}