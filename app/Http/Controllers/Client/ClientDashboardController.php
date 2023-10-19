<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WishListDetails;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplied;

class ClientDashboardController extends Controller
{

    public function dashboard(){
        $userId = Auth::user()->id;
        $appliedJob = JobApplied::where('user_id','=', $userId)->get();
        $datas = WishListDetails::where('created_by', '=', $userId)->get();
        return view('client.dashboard', compact('datas', 'appliedJob'));
    }
}
