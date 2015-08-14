<?php namespace App\Http\Controllers;

use Auth, PushNotification, Input;
use App\User;
use Laracasts\Flash\Flash;

class DashboardController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest');
		$this->middleware('auth', ['only' => 'logged']);
	}

	public function index()
	{
        $data = array();
        array_push($data,['name' => "Broadcast All", 'id' => 'broadcast_all']);
        $users = User::all();
        //dd($users);
        foreach($users as $user){
            array_push($data,['name' => $user->name.' - '.$user->email, 'id' => $user->id] );
        }
		return view('dashboard.index')->with(compact(['data']));
	}

    public function postIndex(){
        if( Input::get('send_to') == "broadcast_all"){
            $users = User::whereNotNull('gcmid')->get();
            foreach($users as $user){
                $title = Input::get('title');
                $type = Input::get('send_to');
                $text = Input::get('send_text');

                $send_text = "Admin#"."broadcast_message"."#".$title."#".$text;


                PushNotification::app(['environment' => 'local',
                    'apiKey'      => 'AIzaSyDJDD5mJcM4ylLWwro60vBRZmexA3nZQ6U',
                    'service'     => 'gcm'])
                    ->to($user->gcmid)
                    ->send($send_text);
            }
        }
        else{
            $user = User::whereNotNull('gcmid')->where('id',Input::get('send_to'))->first();
            $title = Input::get('title');
            $text = Input::get('send_text');

            $send_text = "Admin#"."broadcast_message"."#".$title."#".$text;
            //dd($send_text);

            PushNotification::app(['environment' => 'local',
                'apiKey'      => 'AIzaSyDJDD5mJcM4ylLWwro60vBRZmexA3nZQ6U',//setting server api key
                'service'     => 'gcm'])
                ->to($user->gcmid)
                ->send($send_text);
            //dd($user->toArray());
        }
        Flash::success('Brodacast meessage success ');

        $data = array();
        array_push($data,['name' => "Broadcast All", 'id' => 'broadcast_all']);
        $users = User::all();
        //dd($users);
        foreach($users as $user){
            array_push($data,['name' => $user->name.' - '.$user->email, 'id' => $user->id] );
        }
        return view('dashboard.index')->with(compact(['data']));
    }

}
