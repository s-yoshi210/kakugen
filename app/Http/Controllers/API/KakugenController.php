<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kakugen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KakugenController extends Controller
{


    public function index()
    {
        // @TODO:改善
        $my_kakugens = DB::table('users')
                        ->select('kakugen_id')
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
}
