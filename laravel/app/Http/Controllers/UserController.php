<?php
/**
 * Controller for editing and updating user information
 *
 * @autor Jakub Handzus
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Show editing page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit() {

    	$user = Auth::user();

        return view('user.edit', compact('user'));
    }

    /**
     * Update user information/password
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {

    	$user = Auth::user();

        // form data validation 
		$this->validate(request(), [
			'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'current_password' => 'nullable|string|min:6',
            'password' => 'nullable|required_with:current_password|string|min:6|confirmed|different:current_password'
		]);

    	$user->name = $request->name;
    	$user->surname = $request->surname;
    	$user->email = $request->email;

		if ($request->current_password && !Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is not correct'])->withInput(Input::all());
        }
        else {
        	$user->password = bcrypt($request->password);
        }

    	$user->save();

    	return redirect()->back()->with('success', 'Successfully changed');

    }

}
