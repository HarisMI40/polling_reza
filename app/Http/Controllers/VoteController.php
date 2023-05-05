<?php

namespace App\Http\Controllers;

use App\Models\Choise;
use App\Models\Division;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    function store(Request $request)
    {
        $request->validate(['choise' => ['required']]);

        Vote::create([
            'choise_id' => (int)$request->choise,
            'poll_id' => (int)$request->poll_id,
            'division_id' => Auth::user()->division_id,
            'user_id' => Auth::user()->id
        ]);

        return redirect()->back()->with('voted', 'Voting berhasil !');
    }

    function show()
    {
        $pollId = 1;
        $division = Division::all();
        $choises = Choise::where('poll_id', $pollId)->get();
        $namaDivisiTersedia = [];
        $resultPerDivisi = [];
        $vote = Vote::with(['division', 'choise'])->where('poll_id', $pollId)->get()->groupBy('division.name');
        foreach ($division as $d) {
            array_push($namaDivisiTersedia, array('id' => $d['id'], 'name' => $d['name']));
            if (isset($vote[$d['name']])) {

                array_push($resultPerDivisi, [$d['name'] => collect($vote[$d['name']])->groupBy('choise.choise')]);
            }
        }

        $divisi = collect($resultPerDivisi);


        $datas = $divisi->map(function ($item, $key) {

            $skor = [];
            // Payment
            foreach ($item as $a) {

                //WFO
                foreach ($a as $key => $value) {
                    $skor[$key] = count($value);
                }
            }




            // $prevSkor = $skor;
            $item['skor'] = $skor;

            // foreach ($skor as $key => $itemSkor) {


            //     if ($itemSkor > $prevSkor) {
            //         $data[$key] = 1;
            //     }
            // }

            // $item['skor_item'] = collect($skor)->max();

            return $item;
        });

        return $datas;


        // $collection = collect(['satu' => ['payment' => ['payment1', 'payment2']]    ]);

        // $multiplied = $collection->map(function ($item, $key) {

        //     $data = [];
        //     foreach ($item as $i) {
        //         $data[] = count($i);
        //     }

        //     return $item;
        // });

        // return $multiplied->all();
        // dd(collect($resultPerDivisi)->map(function ($item) {
        //     // $item = true;


        //     return $item;
        // }));


        // $voteBaru = collect([$vote])->map

        return response()->json(['vote' => 'vote']);
    }
}
