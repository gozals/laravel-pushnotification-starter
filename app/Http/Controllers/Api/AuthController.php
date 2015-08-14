<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use App\Role;
use Response;
use Input;
use  Validator;


class AuthController extends Controller {

    public function postLogout(){

        $rules = [	'user_id' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            $result = array();
            $userarray = array();
            $result['status'] = 204;
            $result['message'] = "Parameter Input Kurang";
            $result['data'] = $userarray;
            return Response::json($result);
        }
        if($user = User::find(Input::get('user_id'))){
            $user->gcmid = null;
            $user->save();
        }else{
            $result = array();
            $userarray = array();
            $result['status'] = 404;
            $result['message'] = "User tidak ditemukan";
            $result['data'] = $userarray;
            return Response::json($result);
        }
        $result = array();
        $userarray = array();
        $result['status'] = 200;
        $result['message'] = "OK";
        $result['data'] = $userarray;
        return Response::json($result);
    }

	public function postGcm($user_id){
		$rules = [	'gcmid' => 'required'];
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails())
		{
			$result = array();
			$userarray = array();
			$result['status'] = 204;
			$result['message'] = "Parameter Input Kurang";
			$result['data'] = $userarray;
			return Response::json($result);
		}
		if($user = User::find($user_id)->first()){
			$user->gcmid = Input::get('gcmid');
			$user->save();
			
			$result = array();
			$data = array();
			$data = $user->toArray();
			$result['status'] = 200;
			$result['message'] = "OK";
			$result['data'] = $data;
			return Response::json($result);
		}
				$result = array();
				$userarray = array();
				$result['status'] = 204;
				$result['message'] = "User tidak ditemukan";
				$result['data'] = $userarray;
				return Response::json($result);
		}	

	public function postLogin(){
		$rules = [	'email' => 'required', 'password' => 'required'];
		//$rules = [	'gcmid' => 'required'];
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails())
		{
			$result = array();
			$userarray = array();
			$result['status'] = 204;
			$result['message'] = "Parameter Input Kurang";
			$result['data'] = $userarray;
			return Response::json($result);
		}
		$email = Input::get('email');
		$password = Input::get('password');
		 if (Auth::attempt(['email' => $email, 'password' => $password]))
        {

			//echo var_dump($user);
			$result = array();

			$user = User::where('email', $email)->first();
            $user->gcmid = Input::get('gcmid');
            $user->save();

			$result['status'] = 200;
			$result['message'] = "OK";

			$result['data'] = $user->toArray();

			return Response::json($result);
			
        }
		else{
			$result = array();
			$userarray = array();
			$result['status'] = 204;
			$result['message'] = "Failed Email Password Salah";
			$result['data'] = $userarray;
			return Response::json($result);
		}
	}
	public function postRegister(){
		$rules = [	'name' => 'required',
					'email' => 'required|email',
					'password' => 'required'];
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails())
		{
			$result = array();
			$userarray = array();
			$result['status'] = 204;
			$result['message'] = "Input Kurang atau Salah";
			$result['data'] = $userarray;
			return Response::json($result);
		}
		else{
            if(User::where('email',Input::get('email'))){
                $result = array();
                $result['status'] = 304;
                $result['message'] = "Email sudah terpakai";
                $result['data'] = [];
                return Response::json($result);
            }
		$user = new User ();
        $user -> name = Input::get('name');
        $user -> email = Input::get('email');
        $user -> password = bcrypt(Input::get('password'));
		// tambahin field attach role dan konfirmasi disini
        //$user -> confirmation_code = str_random(32);
        //$user -> confirmed = $request->confirmed;
        $user -> save();
		
		$result = array();
		$userarray = array();
		$userarray = $user->toArray();
		$result['status'] = 200;
		$result['message'] = "OK";
		$result['data'] = $userarray;
		return Response::json($result);
		}
	}

}
