<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Buku;
use Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas      = Buku::all();
        $bookYear   = Buku::whereYear('created_at', date('Y'))->get();
        $bookMonth  = Buku::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get();
        $bookWeek   = Buku::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        
        return view('home', compact('datas','bookMonth','bookYear','bookWeek'));
    }
}
