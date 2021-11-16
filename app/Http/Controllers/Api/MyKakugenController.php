<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MyKakugen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyKakugenController extends Controller
{

    /**
     * ログインユーザーのMy名言取得
     *
     * @return
     */
    public function index()
    {
        $my_kakugens = MyKakugen::with('kakugen')
                        ->where('user_id', Auth::id())
                        ->orderBy('updated_at', 'desc')
                        ->get();
        return $my_kakugens;
    }

}
