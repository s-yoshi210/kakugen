<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use http\Env\Response;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * 登録後のリダイレクトパス
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     *　インスタンス作成　
     *
     * @return void
     */
    public function __construct()
    {
        // もし認証が完了しているならば、HOME画面にリダイレクトする
        // 認証が未完了ならば、続行
        $this->middleware('guest');
    }

    /**
     * リクエストデータ用のValidatorを取得する
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:20'],
            'email'=> ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:16', 'confirmed']
        ]);
    }

    /**
     * ユーザーインスタンスを作成する
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * ユーザー登録後レスポンス
     *
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function registered(Request $request, $user)
    {
        return response()->json([
            'token' => $user->createToken($request->input('device_name'))->accessToken,
            'user'  => $request->user()
        ]);
    }

    /**
     *　ユーザー登録
     *
     * @param Request $request
     * @return Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        // バリデーションチェック
        $this->validator($request->all())->validate();

        // ユーザー作成後、Registeredイベントを発生させる
        // @TODO Registeredイベントでメール送信されない
        event(new Registered($user = $this->create($request->all())));

        // 登録したユーザーでログインを行う
        // auth.phpでデフォルトのガードはweb
        // SessionGuardクラスのlogin()を実行している（認証状態の管理方法はセッション認証）
        $this->guard()->login($user);

        // ユーザー登録後のレスポンスを取得
        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        // ユーザー登録後のレスポンスが取得できなかった場合
        // リクエストがjson形式であるかどうか
        //    true:201(リクエストが成功)ステータスのレスポンスインスタンスを作成
        //    false:HOME画面に遷移
        return $request->wantsJson()
            ? new Response('', 201)
            : redirect($this->redirectPath());
    }

}
