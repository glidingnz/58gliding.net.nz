<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;
use App\User;
use App\Facades\Messages;
use App\Http\Requests;
use App;
use Auth;
use Validator;
use Mail;

class UserController extends Controller
{
	public function register(Request $request)
	{
		return view('auth/register');
	}


	public function view_account()
	{
		$user = Auth::user();
		$member = null;
		if ($user->gnz_id>0 && $user->gnz_active==1)
		{
			$member = Member::where('nzga_number', $user->gnz_id)->first();
		}

		return view('user/account', ['user'=>$user, 'member'=>$member]);
	}

	public function update_account(Request $request)
	{
		Validator::extend('foo', function($attribute, $value, $parameters, $validator) {
			return $value == 'foo';
		});

		$user = Auth::user();
		$validator = Validator::make($request->all(), [
			'email' => 'required|email|unique:users,email,' . $user->id,
			'first_name' => 'required',
			'last_name' => 'required',
			'gnz_id' => 'integer|nullable'
		]);

		if ($validator->passes())
		{
			// update details
			$updated_user = App\User::find($user->id);
			$updated_user->email = $request->input('email');

			// check if we need to validate the email again
			if ($user->email != $request->input('email') || $request->input('resend-validation'))
			{
				$updated_user->activated=false;
				$updated_user->activation_code = str_random(10); // create an activation code
				$request->session()->flash('warning', 'You\'ll need to re-validate your email address. Check your email and follow the instructions');
				$this->send_activation_email($updated_user->email, $updated_user->activation_code);
			}

			// check if we have changed GNZ number
			if ($user->gnz_id != $request->input('gnz_id'))
			{
				$updated_user->gnz_active=false;
			}

			// check validation of GNZ number
			if ($updated_user->activated && $updated_user->gnz_active==false)
			{
				$gnz_id = $updated_user->gnz_id;
				if ($request->input('gnz_id')) $gnz_id = $request->input('gnz_id');


				if ($gnz_account = App\Models\Member::where('nzga_number', $gnz_id)->first())
				{
					if ($gnz_account->email==$user->email)
					{
						$updated_user->gnz_active = true;
					}
					else
					{
						$updated_user->gnz_active = false;
					}
				}
				
			}

			$updated_user->first_name = $request->input('first_name');
			$updated_user->last_name = $request->input('last_name');
			$updated_user->gnz_id = $request->input('gnz_id');


			if ($updated_user->gnz_id=='') $updated_user->gnz_id=null;


			$updated_user->save();

			$request->session()->flash('success', 'Account Updated');
		}
		return redirect('/user/account')->withInput()->withErrors($validator);
	}




	public function create(Request $request)
	{
		$this->validate($request, [
			'email' => 'bail|required|email|unique:users'
		]);

		$user = new User;
		$user->email = $request->input('email');
		$user->activation_code = str_random(10); // create an activation code
		$user->save();

		$this->send_activation_email($user->email, $user->activation_code);
		Messages::success('Account Created. Check your email to activate your account.');
		return view('auth/account-created');
	}



	public function activate_post(Request $request)
	{

		$this->validate($request, [
			'code' => 'required',
			'first_name' => 'required',
			'last_name' => 'required',
			'mobile' => 'required',
			'password' => 'required|min:4|confirmed',
			'gnz_id' => 'integer',
		]);

		if ($user = User::where('activation_code', $request->input('code'))->first())
		{
			$user->first_name = $request->input('first_name');
			$user->last_name = $request->input('last_name');
			$user->mobile = $request->input('mobile');
			$user->password = bcrypt($request->input('password'));
			$user->activated = 1;
			$user->gnz_id = $request->input('gnz_id')=='' ? null : $request->input('gnz_id');
			$user->activation_code = null;
			$user->save();
			$request->session()->flash('success', 'Account Activated. You are now logged in.');

			Auth::login($user, true);
			return redirect('/user/account');
		}
		else
		{
			Messages::error('Activation code not valid');
		}

		return view('blank');
	}




	public function activate(Request $request)
	{

		if ($user = User::where('activation_code', $request->input('code'))->first())
		{

			// get details from GNZ database
			if ($gnz_member = App\Models\Member::where('email', $user->email)->first())
			{
				$user->first_name=$gnz_member->first_name;
				$user->last_name=$gnz_member->last_name;
				$user->gnz_id=$gnz_member->nzga_number;
				$user->mobile = simple_string($gnz_member->mobile_phone, ' ');
				$user->gnz_active=true;
				$user->save();
			}


			return view('auth/activate', ['code' => $request->input('code'), 'user'=>$user]);
		}

		Messages::error('Acitvation code not valid');
		return view('blank');
	}



	public function send_activation_email($new_email, $activation_code)
	{
		$user = Auth::user();
		$url = App::make('url')->to('/activate') . '?code=' . $activation_code;

		Mail::send('emails.activateaccount', ['user' => $user, 'url' => $url], function ($m) use ($new_email) {
			$m->from('tim@pear.co.nz', 'Gliding New Zealand');
			$m->to($new_email)->subject('Activate your GNZ Account');
		});
	}
}
