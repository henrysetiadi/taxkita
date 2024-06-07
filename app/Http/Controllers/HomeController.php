<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function user(Request $request)
    {
        //return response()->json(auth()->user());
        $user = auth()->user();
        $result = array();
        if($user)
        {
           $dataUser = User::get();

            if(count($dataUser)>0)
            {
                foreach($dataUser as $key => $val)
                {
                    $result[$key]['name']= $val->name;
                    $result[$key]['email']= $val->email;
                }
            }
        }
        else{
            $dataUser = User::get();

            if(count($dataUser)>0)
            {
                foreach($dataUser as $key => $val)
                {
                    $result[$key]['name']= $val->name;
                }
            }
        }
        return response()->json($result);
    }

}
