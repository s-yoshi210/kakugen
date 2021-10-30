<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MyKakugen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function store(Request $request, string $kakugen_id)
    {
        $rules = [
            'comment' => ['required', 'string', 'max:1000']
        ];

        Validator::make($request->all(), $rules)->validate();

        MyKakugen::updateOrCreate([
            'user_id' => Auth::id(),
            'kakugen_id' => $kakugen_id
        ], [
            'comment' => $request['comment']
        ]);
    }

    public function update(Request $request, string $kakugen_id)
    {
        $rules = [
            'comment' => ['required', 'string', 'max:1000']
        ];

        Validator::make($request->all(), $rules)->validate();

        MyKakugen::updateOrCreate([
            'user_id' => Auth::id(),
            'kakugen_id' => $kakugen_id
        ], [
            'comment' => $request['comment']
        ]);
    }

    public function destroy(Request $request, string $kakugen_id)
    {
        $my_kakugen = MyKakugen::where('user_id', Auth::id())
            ->where('kakugen_id', $kakugen_id)
            ->first();

        if($my_kakugen) {
            $my_kakugen->comment = null;
            $my_kakugen->save();
        }

    }
}
