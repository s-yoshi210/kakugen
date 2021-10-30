<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MyKakugen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * お気に入り登録
     *
     * @param string $kakugen_id
     */
    public function store(string $kakugen_id)
    {
        $my_kakugen = MyKakugen::updateOrCreate([
            'user_id' => Auth::id(),
            'kakugen_id' => $kakugen_id
        ], [
            'favorite' => true
        ]);
    }

    /**
     * お気に入り解除
     *
     * @param string $kakugen_id
     */
    public function destroy(string $kakugen_id)
    {
        $my_kakugen = MyKakugen::where('user_id', Auth::id())
            ->where('kakugen_id', $kakugen_id)
            ->first();

        if($my_kakugen) {
            $my_kakugen->favorite = false;
            $my_kakugen->save();
        }
    }
}
