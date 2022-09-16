<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class GeneralController extends Controller {
    function postLogIn(Request $request) {
        $Model = Users::where('email', $request->email)->where('password', md5($request->password))->first();

        if (!empty($Model)) {
            Session::put('email', $Model->email);
            Session::put('name', 'ADMIN');
            Session::put('role', 'admin');
            return redirect('master/home-page');
        } else {
            Session::put('denied', 'Username or Password isn\'t right');
            return redirect('/');
        }
    }

    function getLogOut() {
        $role = Session::get('role');
        Session::flush();
        Cache::flush();
        return redirect('/');
    }
}
