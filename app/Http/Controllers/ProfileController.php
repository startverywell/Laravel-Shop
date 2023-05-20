<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user_id = $request->input('user_id');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user_id,
            'new_password' => 'nullable|min:8|max:12|required_with:current_password',
            'password_confirmation' => 'nullable|min:8|max:12|required_with:new_password|same:new_password'
        ];

        // 'current_password' => 'nullable|required_with:new_password',

        $request->validate($rules);


        $user = User::findOrFail($user_id);
        $user->name = $request->input('name');
        $user->full_name = $request->input('full_name');
        $user->email = $request->input('email');
        $user->profile_url = $request->input('profile_url');

        if (!is_null($request->input('current_password'))) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = bcrypt($request->input('new_password'));
            } else {
                return redirect()->back()->withInput();
            }
        } else {
            if (Auth::user()->isAdmin()) {
                $user->password = bcrypt($request->input('new_password'));
            }
        }

        $user->save();

        if ($user_id == Auth::user()->id) {
            return redirect()->route('profile')->withSuccess('Profile updated successfully.');
        } else {
            return redirect()->route('admin.user.edit', $user_id)->withSuccess('Profile updated successfully.');
        }
    }
}
