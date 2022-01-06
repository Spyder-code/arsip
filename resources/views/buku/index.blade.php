@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#table').DataTable();

} );
</script>
@stop
@extends('layouts.app')

@section('content')
<div class="row">

  <div class="col-lg-2">
    <div class="d-sm-flex">
      <a href="{{ route('buku.create') }}" class="btn btn-primary btn-rounded m-1"><i class="fa fa-plus"></i> Tambah Buku</a>
      <a href="{{ route('export.excel',['type'=>$type]) }}" class="btn btn-success btn-rounded m-1"><i class="fa fa-download"></i> Export Excel</a>
      @if ($type==4)
        <a href="{{ route('export.doc.custom',['from' =>$from ,'to'=>$to]) }}" class="btn btn-info btn-rounded m-1"><i class="fa fa-download"></i> Export Word</a>
      @else
        <a href="{{ route('export.doc',['type' =>$type]) }}" class="btn btn-info btn-rounded m-1"><i class="fa fa-download"></i> Export Word</a>
      @endif
    </div>
  </div>
    <div class="col-lg-12">
                  @if (Session::has('message'))
                  <div class="alert alert-{{ Session::get('message_type') }}" id="waktu2" style="margin-top:10px;">{{ Session::get('message') }}</div>
                  @endif
                  </div>
</div>
<div class="row" style="margin-top: 20px;">
<div class="col-lg-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <div class="card-title pull-left border-right border-dark">
                    <h4>Filter data:</h4>
                    <form action="{{ route('filter') }}" method="post">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label class="mx-3"><input type="radio" onclick="submit()" {{ $type==0?'checked':''}} value="all" name="filter" class="form-control">Semua</label>
                        <label class="mx-3"><input type="radio" onclick="submit()" {{ $type==1?'checked':''}} value="week" name="filter" class="form-control">Minggu ini</label>
                        <label class="mx-3"><input type="radio" onclick="submit()" {{ $type==2?'checked':''}} value="month" name="filter" class="form-control">Bulan ini</label>                        
                        <label class="mx-3"><input type="radio" onclick="submit()" {{ $type==3?'checked':''}} value="year" name="filter" class="form-control">Tahun ini</label>
                        <label class="mx-3"><input type="radio" onclick="submit()" value="4" disabled name="filter" {{ $type==4?'checked':'' }} class="form-control">Custom</label>
                      </div>
                    </form>
                    <label>Tahun</label>
                    <select onchange="location = this.value;" class="form-control mr-3">
                      <option value="{{ route('buku.index',['year'=>'2021']) }}" {{ $year=='2021'?'selected':'' }}>2021</option>
                      <option value="{{ route('buku.index',['year'=>'2022']) }}" {{ $year=='2022'?'selected':'' }}>2022</option>
                      <option value="{{ route('buku.index',['year'=>'2023']) }}" {{ $year=='2023'?'selected':'' }}>2023</option>
                      <option value="{{ route('buku.index',['year'=>'2024']) }}" {{ $year=='2024'?'selected':'' }}>2024</option>
                    </select>
                  </div>
                  <div class="card-title pull-right">
                    <form action="{{ route('filter.custom') }}" method="post">
                      {{ csrf_field() }}
                      <div class="form-group row">
                        <div class="col">
                          <label>Dari:</label>
                          <input type="date" name="from" class="form-control">
                        </div>
                        <div class="col">
                          <label>Sampai:</label>
                          <input type="date" name="to" class="form-control">
                        </div>
                        <div class="col">
                          <button type="submit" class="btn btn-success mt-4">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped wrapper" id="table">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>Dari</th>
                          <th>Untuk</th>
                          <th>Nomor_surat</th>
                          <th>Perihal</th>
                          <th>Tahun</th>
                          <th>Tanggal masuk</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($datas as $item)
                        <tr>
                          <td class="py-1">
                            <a href="{{url('surat/'.$item->id)}}" target="d_blank"> 
                              {{$item->no}}
                            </a>
                          </td>
                          <td>{{ $item->dari }}</td>
                          <td>{{ $item->untuk }}</td>
                          <td>
                            <a href="{{url('surat/'.$item->id)}}" target="d_blank"> 
                              {{$item->nomor_surat}}
                            </a>
                          </td>
                          <td>
                            <a href="{{url('surat/'.$item->id)}}" target="d_blank"> 
                              {{str_limit($item->perihal,30)}}
                            </a>
                          </td>
                          <td>{{$item->tahun}}</td>
                          <td>{{date('d/m/y', strtotime($item->created_at))}}</td>
                          <td>
                            <div class="btn-group dropdown">
                              <button type="button" class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                              </button>
                              <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                                <a class="dropdown-item" href="{{url($item->file)}}"> Download surat </a>
                                <a class="dropdown-item" href="{{route('buku.edit', $item->id)}}"> Edit </a>
                                <form action="{{ route('buku.destroy', $item->id) }}" class="pull-left"  method="post">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <button class="dropdown-item" onclick="return confirm('Anda yakin ingin menghapus data ini?')"> Delete
                                </button>
                              </form>
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection