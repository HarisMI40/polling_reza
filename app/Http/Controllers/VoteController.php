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


        $datas = $divisi->map(function ($item, $key) use ($choises, $division) {

            $skor = [];
            // Payment
            foreach ($item as $a) {

                //WFO
                foreach ($a as $key => $value) {
                    $skor[$key] = count($value);
                }
            }

            $skor_item = collect($skor)->max();

            $skor_sementara = [];
            foreach ($skor as $key => $value) {
                if ($value === $skor_item) {
                    $skor_sementara[$key] = 1;
                }
            }

            $count_skor_result = count($skor_sementara);
            if ($count_skor_result > 1) {
                foreach ($skor_sementara as $key => $value) {
                    $skor_sementara[$key] = 1 / $count_skor_result;
                }
            }

            $choisesName = [];
            foreach ($choises as $key => $choise) {
                $choisesName[] = $choise['choise'];
            }

            foreach ($division as $div) {
                if (isset($item[$div['name']])) {
                    unset($item[$div['name']]);
                    $item[$div['name']] = $skor_sementara;
                    break;
                }
            }

            return $item;
        });

        // nilai skor awal choises berdasarkan divisi 
        return $nilai_choise_awal = $choises->map(function ($item, $key) use ($division) {
            $result = [];
            $result[$item['choise']] = 0;
            $divisions = [];
            foreach ($division as $divisi) {
                $divisions[$divisi['name']] = $result;
            }
            return $result;
        })->collapse(); 

        // nilai sementara skor choises berdasarkan divisi
        return collect($datas)->map(function ($item) {
            $result = [];
            foreach ($item as $key => $value) {
                //if ($wfo) //
            }

            return $item;
        });

        return response()->json(['vote' => 'vote']);
    }
}
