<?php

namespace App\Helpers;

use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class PollResult
{
    static function user($id)
    {
        return Poll::where('id', $id)->withCount('votes')->with(['choises.votes' => function (Builder $q) use ($id) {
            $q->where('poll_id', $id);
        }])->withCount(['votes as voted' => function (Builder $q) {
            return $q->where('user_id', Auth::user()->id);
        }])->get()->map(function ($poll) {
            $poll->is_deadline = strtotime($poll['deadline']) <= strtotime(date('Y-m-d h:i:s')) ? true : false;
            collect($poll['choises'])->map(function ($choise) use ($poll) {
                $choise->votes_count = count($choise->votes);
                $choise->result_persentase = $poll['votes_count'] == 0 ? 0 : round($choise->votes_count / $poll['votes_count'] * 100);
                unset($choise->votes);
                return $choise;
            });
            return $poll;
        })->first();
    }

    static function tes($id)
    {
        return Poll::where('id', $id)->withCount('votes')->with(['choises.votes' => function (Builder $q) use ($id) {
            $q->where('poll_id', $id)->groupBy('division_id');
        }])->get();

        return Poll::where('id', $id)->withCount('votes')->with(['choises.votes' => function (Builder $q) use ($id) {
            $q->where('poll_id', $id)->groupBy('division_id');
        }])->withCount(['votes as voted' => function (Builder $q) {
            return $q->where('user_id', Auth::user()->id);
        }])->get()->map(function ($poll) {
            $poll->is_deadline = strtotime($poll['deadline']) <= strtotime(date('Y-m-d h:i:s')) ? true : false;
            collect($poll['choises'])->map(function ($choise) use ($poll) {
                $choise->votes_count = count($choise->votes);
                $choise->result_persentase = $poll['votes_count'] == 0 ? 0 : round($choise->votes_count / $poll['votes_count'] * 100);
                unset($choise->votes);
                return $choise;
            });
            return $poll;
        })->first();
    }

    static function admin($id)
    {
        return Poll::where('id', $id)->withCount('votes')->with(['choises.votes' => function (Builder $q) use ($id) {
            $q->where('poll_id', $id);
        }])->withCount(['votes as voted' => function (Builder $q) {
            return $q->where('user_id', Auth::user()->id);
        }])->get()->map(function ($poll) {
            $poll->is_deadline = strtotime($poll['deadline']) < strtotime(date('Y-m-d h:i:s')) ? true : false;
            collect($poll['choises'])->map(function ($choise) use ($poll) {
                $choise->votes_count = count($choise->votes);
                $choise->result_persentase = $poll['votes_count'] == 0 ? 0 : round($choise->votes_count / $poll['votes_count'] * 100);
                unset($choise->votes);
                return $choise;
            });
            return $poll;
        })->first();
    }
    static function admin_2($id)
    {


        // {
        //     "cara_1" :  {
        //           "1": {
        //               "user_1": "wfo",
        //               "user_2": "wfh",
        //               "user_3": "wfh",
        //               "user_4": "wfh",
        //               "user_5": "wfh",
        //               "user_6": "wfh"
        //           }
        //       },



        //     "cara_2" :  {
        //           "divisi_it": {
        //               "wfo": 1,
        //               "wfh": 6
        //           }
        //       }
        //   }


        // {
        //     "data": [
        //     {

        //     "division_id": 1,
        //     },
        //     {
        //     "id": 18,
        //     "choise_id": 6,
        //     "user_id": 2,
        //     "poll_id": 1,
        //     "division_id": 1,
        //     "created_at": null,
        //     "updated_at": null
        //     },
        //     {
        //     "id": 19,
        //     "choise_id": 6,
        //     "user_id": 3,
        //     "poll_id": 1,
        //     "division_id": 2,
        //     "created_at": null,
        //     "updated_at": null
        //     },
        //     {
        //     "id": 20,
        //     "choise_id": 6,
        //     "user_id": 4,
        //     "poll_id": 1,
        //     "division_id": 3,
        //     "created_at": null,
        //     "updated_at": null
        //     },
        //     {
        //     "id": 21,
        //     "choise_id": 8,
        //     "user_id": 5,
        //     "poll_id": 1,
        //     "division_id": 3,
        //     "created_at": null,
        //     "updated_at": null
        //     },
        //     {
        //     "id": 22,
        //     "choise_id": 8,
        //     "user_id": 6,
        //     "poll_id": 1,
        //     "division_id": 3,
        //     "created_at": null,
        //     "updated_at": null
        //     },
        //     {
        //     "id": 23,
        //     "choise_id": 8,
        //     "user_id": 7,
        //     "poll_id": 1,
        //     "division_id": 3,
        //     "created_at": null,
        //     "updated_at": null
        //     },
        //     {
        //     "id": 24,
        //     "choise_id": 8,
        //     "user_id": 8,
        //     "poll_id": 1,
        //     "division_id": 3,
        //     "created_at": null,
        //     "updated_at": null
        //     },
        //     {
        //     "id": 25,
        //     "choise_id": 8,
        //     "user_id": 9,
        //     "poll_id": 1,
        //     "division_id": 3,
        //     "created_at": null,
        //     "updated_at": null
        //     }
        //     ]
        //     }

        $votes = Vote::where(['poll_id' => 1])->get();
        return response()->json([
            'data' => $votes
        ]);
    }
}
