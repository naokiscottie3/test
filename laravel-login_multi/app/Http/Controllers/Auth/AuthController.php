<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(){
        return view('login.login_form');
    }

    public function login(LoginFormRequest $request){
      $credentials = $request->only('email', 'password');

      $user = User::where('email', '=', $credentials['email'])->first();
      //取得したフォームのメールアドレスのデータがあるのか。
        if(!is_null($user)){
            //その行のlocked_flgを確認する。1(ロックされている状態)であれば、フォームに戻る。
            if($user->locked_flg === 1){
                return back()->withErrors([
                    'login_error' => 'アカウントがロックされています。',
            ]);
            }

            //アカウント認証を行い、アカウント認証が問題なければホームに移る。そして、その前にerror_countがあればリセットする。
            if(Auth::attempt($credentials)){
                $request->session()->regenerate();
                //成功したらエラーアカウントを0にする。
                if($user->error_count > 0){
                    $user->error_count = 0;
                    $user->save();
                }

                return redirect('home')->with('login_success','ログイン成功しました！');
            }

            //ログイン処理に失敗した時，エラーアカウントを1増やす
            $user->error_count = $user->error_count + 1;

            //エラーアカウントが6以上の時，アカウントをロックする。その後、フォーム画面に戻る。
            if($user->error_count > 5){
                $user->locked_flg = 1;
                $user->save();

                return back()->withErrors([
                        'login_error' => 'アカウントがロックされました。',
                ]);
            }

            $user->save();

        }
      return back()->withErrors([
            'login_error' => 'メールアドレスかパスワードが間違っています。',
      ]);



    }

    /**
     * ユーザーをアプリケーションからログアウトさせる
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login.show')->with
        ('logout','ログアウトました。');
    }



}
