<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Buku;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($year)
    {
        $datas = Buku::whereYear('created_at', $year)->get();
        $type = 0;
        return view('buku.index', compact('datas','type','year'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('buku.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'dari' => 'required',
            'untuk' => 'required',
            'perihal' => 'required',
            'nomor_surat' => 'required',
        ]);

        $year = intval(date('Y'));
        $no = DB::table('buku')->where('tahun',$year)->max('no') + 1;
        $file = $request->file('file');
        $format = $file->getClientOriginalExtension();
        Buku::create([
            'dari' => $request->dari,
            'untuk' => $request->untuk,
            'perihal' => $request->perihal,
            'nomor_surat' => $request->nomor_surat,
            'tahun' => $year,
            'no' => $no,
            'file' => '/storage/surat/'.$no. '-'.$year.'-'.$request->untuk.'.'.$format
        ]);

        $file->storeAs('public/surat/',$no.'-'.$year.'-'.$request->untuk.'.'.$format);

        alert()->success('Berhasil.','Data telah ditambahkan!');

        return redirect()->route('buku.index',['year'=>date('Y')]);

    }

    public function custom(Request $request)
    {
        $datas = Buku::whereBetween('created_at', [$request->from, $request->to])->get();
        $type = 4;
        $from = $request->from;
        $to = $request->to;
        return view('buku.index',compact('datas','type','from','to'));
    }

    public function filter(Request $request)
    {
        return redirect('buku/filter/'.$request->filter);
    }

    public function filterName($name)
    {
        if ($name=="all") {
            return redirect()->route('buku.index',['year'=>date('Y')]);
        }
        if ($name=="week") {
            $type = 1;
            $datas = Buku::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        }
        if ($name=="month") {
            $type = 2;
            $datas = Buku::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get();
        }
        if ($name=="year") {
            $type = 3;
            $datas = $datas = Buku::whereYear('created_at', date('Y'))->get();
        }
        return view('buku.index',compact('datas','type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $data = Buku::findOrFail($id);
        return view('buku.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'dari' => 'required',
            'untuk' => 'required',
            'perihal' => 'required',
            'nomor_surat' => 'required',
        ]);
        Buku::find($id)->update($request->all());

        alert()->success('Berhasil.','Data telah diubah!');
        return redirect()->route('buku.index',['year'=>date('Y')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Buku::find($id)->delete();
        $max = DB::table('buku')->max('id') + 1; 
        DB::statement("ALTER TABLE buku AUTO_INCREMENT =  $max");
        $year = intval(date('Y'));
        $buku = Buku::all()->where('tahun',$year);
        $no = 1;
        foreach ($buku as $item ) {
            Buku::find($item->id)->update([
                'no' => $no
            ]);
            $no++;
        };
        alert()->success('Berhasil.','Data telah dihapus!');
        return redirect()->route('buku.index');
    }
}
