<?php

namespace App\Exports;

use App\Buku;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BukuExport implements FromView
{
    protected $type;
    protected $from;
    protected $to;

    function __construct($type, $from = null, $to = null) {
            $this->type = $type;
            $this->from = $from;
            $this->to = $to;
    }

    public function view(): View
    {
        $type = $this->type;
        if ($type==0) {
            $data = Buku::all();
        }
        if ($type==1) {
            $data   = Buku::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        }
        if ($type==2) {
            $data  = Buku::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get();
        }
        if ($type==3) {
            $data   = Buku::whereYear('created_at', date('Y'))->get();
        }
        if ($type==4) {
            $data   = Buku::whereBetween('created_at', [$this->from, $this->to])->get();
        }
        return view('exports.book',compact('data'));
    }
}
