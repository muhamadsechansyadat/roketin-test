<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompareTimeController extends Controller
{
    public function index()
    {
        return view('compare-time.index');
    }

    function timeToSeconds(string $time): int
    {
        $arr = explode(':', $time);
        if (count($arr) === 3) {
            return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
        }
        return $arr[0] * 60 + $arr[1];
    }

    public function action(Request $request)
    {
        $second = $this->timeToSeconds($request->time);
        $hour = round($second / 3600);

        $roketSecond = $hour * 60 * 60;
        $roketHour = $roketSecond;
        dd($roketHour, $request->time);
        return 'asdf';
    }


}
