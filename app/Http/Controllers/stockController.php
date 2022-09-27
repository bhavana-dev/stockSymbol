<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\User;
use Session;
use Auth;
class stockController extends Controller
{
    public function fbLogin(){
        return view('fbLogin');
    }

    public function fbLoginPost(Request $request){
        $data = $request->all();
        $data = $data['data'];
        if($already=User::where(['name'=>$data['name']])->first()){
            Auth::Login($already, true);
            return "success";
        }else{
          $authUser = $this->createUser($data);
          Auth::Login($authUser, true);
          return "success";
        }    
        return "error";
    }
        
    protected function createUser($user)
   {
        $authUser = User::where('social_id',$user['id'])->first();
       if($authUser){
           return $authUser;
       }
        $random_key = mt_rand(10000000, 9999999999);    
        return User::create([
            'email' => '',
            'password' => $random_key,
            'name' => $user['name'],
            'social_id' => $user['id'],
           
        ]);
   }

    public function index(){
        $stocks = Stock::get();
        return view('stock',compact('stocks'));
    }

    public function  fbLogout(){
        Session::flush();
        Auth::logout();
        return redirect('/fbLogin');
    }

    public function saveData(Request $request){
       $data = $request->all();
       $data = $data['response']['Global Quote'];
       $stocks= Stock:: create([
            'symbol'=> $data['01. symbol'],
            'low'=> $data['04. low'],
            'high'=> $data['03. high'],
            'price'=> $data['05. price'],
       ]);

       return "success";

    }
}
