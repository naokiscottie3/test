<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use App\Http\Requests\CreateAdminUserLoginRequest;
use App\Models\AdminUsers;
use Illuminate\Support\Facades\Hash;



class AdminLoginController extends Controller
{
    public function index(){
        return view('admin_login_form');
    }

    public function signInAdmin(CreateAdminUserLoginRequest $request){
        if(Auth::guard('admin')->attempt(['email'=>$request['email'],'password'=>$request['password']])){
            return redirect()->route('admin');
        }

        return redirect()->route('admin_login');

    }


        //adminユーザー新規登録
    public function admin_registar_show(){
        return view('new_user');
    }

    //adminユーザー新規登録
    public function admin_registar(Request $request)
    {

        //DB::beginTransactionを入れることによってDB処理、今回の場合は登録がcommitを行わないと完結しない状態となる。
        \DB::beginTransaction();
        //tryの中で何かのエラーが起きた場合、catchの{}に移動する。
        try{
            AdminUsers::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),

            ]);
            \DB::commit();
        }catch(\Throwable $e){
            \DB::rollback();
            abort(500);
        }
        \Session::flash('err_msg', 'ユーザーを登録しました。');

        return redirect(route('admin_register_show'));

    }
}
