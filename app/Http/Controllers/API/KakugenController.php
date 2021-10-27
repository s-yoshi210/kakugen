<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kakugen;
use App\Models\MyKakugen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KakugenController extends Controller
{


    public function index()
    {
        // @TODO:改善
        $my_kakugens = DB::table('users')
                        ->select('my_kakugens.id as my_kakugen_id', 'kakugen_id', 'favorite', 'comment')
                        ->join('my_kakugens', 'users.id', '=', 'my_kakugens.user_id')
                        ->where('users.id', '=', Auth::id());

        $kakugens = DB::table('kakugens')
                        ->leftJoinSub($my_kakugens, 'user_kakugens', function ($join) {
                            $join->on('kakugens.id', '=', 'user_kakugens.kakugen_id');
                        })->whereNull('user_kakugens.kakugen_id')
                        ->inRandomOrder()
                        ->take(3)
                        ->get();

        return $kakugens;

    }

    public function favorite(string $kakugen_id)
    {
        $my_kakugen = MyKakugen::updateOrCreate([
            'user_id' => Auth::id(),
            'kakugen_id' => $kakugen_id
        ], [
            'favorite' => true
        ]);
    }

    public function unfavorite(string $kakugen_id)
    {
        $my_kakugen = MyKakugen::where('user_id', Auth::id())
                        ->where('kakugen_id', $kakugen_id)
                        ->first();

        if($my_kakugen) {
            $my_kakugen->favorite = false;
            $my_kakugen->save();
        }
    }

    public function addComment(Request $request, string $kakugen_id)
    {

        // バリデーション実行
        $rules = [
            'comment' => ['required', 'string', 'max:1000']
        ];

        Validator::make($request->all(), $rules)->validate();

        // コメント登録
        MyKakugen::updateOrCreate([
            'user_id' => Auth::id(),
            'kakugen_id' => $kakugen_id
        ], [
            'comment' => $request['comment']
        ]);
    }
}
