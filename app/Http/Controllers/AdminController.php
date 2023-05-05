<?php

namespace App\Http\Controllers;

use App\Helpers\PollResult;
use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    function index()
    {
        $data['users'] = User::where('role', 'user')->get()->count();
        $votings = Vote::all()->count();
        $data['pollings'] = Poll::where('created_by', Auth::user()->id)->get()->count();
        $data['votings'] = $votings;

        return view('admin.dashboard', $data);
    }

    function users()
    {
        $data['users'] = User::with('divisi')->where('role', 'user')->get();

        return view('admin.users', $data);
    }

    function pollings()
    {
        $polls = Poll::withCount(['choises', 'votes', 'votes as voted' => function (Builder $q) {
            return $q->where('user_id', Auth::user()->id);
        }])->orderBy('created_at', 'desc')->get();
        return view('admin.polls', [
            'polls' => $polls
        ]);
    }

    function showPolling(string $id)
    {

      



        $data['poll'] = PollResult::admin_2($id);
        return $data['poll'];


        // return view('admin.poll', $data);
    }
}
