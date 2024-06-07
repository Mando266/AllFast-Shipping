<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function edit(){
        return view('admin.user.reset-password');
    }

    public function update(Request $request){
        $request->validate($this->rules());
        $user = $request->user();
        $this->setUserPassword($user,$request->input('password'));
        $user->save();
        return redirect()->route('home')->with('success',trans('login.password_updated'));
    }

    protected function setUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
    }

    protected function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
        ];
    }
}
