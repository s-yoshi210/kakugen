<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * ログイン後のリダイレクト先
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        // もし認証が完了しているならばHOMEにリダイレクトする
        // ただし、logout処理が呼ばれたときは除く
        $this->middleware('guest')->except('logout');
    }

    /**
     * ログイン成功レスポンス
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    protected function sendLoginResponse(Request $request)
    {
        // 指定されたユーザー認証情報のログインロックを解除
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return response()->json([
            'token' => $request->user()->createToken($request->input('device_name'))->accessToken,
            'user' => $request->user()
        ]);
    }


    /**
     * ログイン処理
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // 入力された情報をバリデーションチェック
        $this->validateLogin($request);

        // ユーザーのログイン失敗回数が多すぎるかどうかを判断する
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            // 上限を超えたので、ロックアウト（締め出す）イベント発生
            $this->fireLockoutEvent($request);

            // ロックアウトレスポンスを返す（429ステータス）
            return $this->sendLockoutResponse($request);
        }

        // ログインを試みる
        if ($this->attemptLogin($request)) {
            // 成功
            // @TODO sendLoginResponseの内容の違いを確認
            return $this->sendLoginResponse($request);
        }

        // ログイン試行回数増加
        $this->incrementLoginAttempts($request);

        // ログイン失敗レスポンスを返す
        return $this->sendFailedLoginResponse($request);
    }

}
