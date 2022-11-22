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
    			->first();
    		
    		if ($data) {
				$trader = DB::connection('oracle-eproc')
					->table(DB::raw('"m_trader"'))
					->where('trader_id', $data->trader_id)
					->first();
    			$user = User::updateOrCreate(
    				[
    					'username' => $data->username,
    					'password' => $data->password
    				],
    				[
    					'user_id' => $data->user_id,
    					'trader_id' => $data->trader_id,
    					'vendor_id' => $trader->trader_id ?? null,
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