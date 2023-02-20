@extends('layouts.app')

@section('title', 'Rekap Kehadiran')

@section('content')
@php
    if (isset($bulan)) {
      $bulan = $bulan;
    } else {
      $bulan = 1;
    }
@endphp

  <div class="row">
    <div class="col-sm-12 m-0 p-0">
      @if (Session::get('success'))
        <div class="alert alert-success alert-solid alert-dismissible fade show p-2 " role="alert">
          <span>{{ Session::get('success') }}</span>
          <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <div class="header-title">
            <h5 class="card-title">Rekap Kehadiran</h5>
          </div>
        </div>
        <div class="card-body" style="font-size: 14px;">

          <form action="{{ route('admin.rekap.kehadiran') }}" method="POST">
            @csrf
            <div class="row justify-content-start mb-2 ps-4">
              <div class="col-3">
                  <div class="form-group">
                      <select name="bulan" class="form-select form-control form-control-sm" id="month">
                          <option value="1" {{ $bulan == 1 ? 'selected' : '' }}>Januari</option>
                          <option value="2" {{ $bulan == 2 ? 'selected' : '' }}>Februari</option>
                          <option value="3" {{ $bulan == 3 ? 'selected' : '' }}>Maret</option>
                          <option value="4" {{ $bulan == 4 ? 'selected' : '' }}>April</option>
                          <option value="5" {{ $bulan == 5 ? 'selected' : '' }}>Mei</option>
                          <option value="6" {{ $bulan == 6 ? 'selected' : '' }}>Juni</option>
                          <option value="7" {{ $bulan == 7 ? 'selected' : '' }}>Juli</option>
                          <option value="8" {{ $bulan == 8 ? 'selected' : '' }}>Agustus</option>
                          <option value="9" {{ $bulan == 9 ? 'selected' : '' }}>September</option>
                          <option value="10" {{ $bulan == 10 ? 'selected' : '' }}>Oktober</option>
                          <option value="11" {{ $bulan == 11 ? 'selected' : '' }}>November</option>
                          <option value="12" {{ $bulan == 12 ? 'selected' : '' }}>Desember</option>S
                      </select>
                  </div>
              </div>

              {{-- <div class="col-3">
                  <div class="form-group">
                      <select class="form-select form-control form-control-sm" id="exampleFormControlSelect1">
                          <option value="2022">2022</option>
                      </select>
                  </div>
              </div> --}}

              <div class="col-3">
                <div class="form-group">
                  <button type="submit" class="btn btn-primary">Tampilkan Data</button>
                </div>
              </div>

              @if (isset($users))
              <div class="col-6">
                <div class="form-group">
                  <a class="btn btn-danger m-1 float-end" href="{{ route('admin.export.absen', ['bulan' => $bulan, 'tipe' => 'pdf']) }}">Export PDF</a>
                  <a class="btn btn-success m-1 float-end" href="{{ route('admin.export.absen', ['bulan' => $bulan, 'tipe' => 'excel']) }}">Export Excel</a>
                </div>
              </div>
              @endif

            </div>

          </form>

          @if (isset($users))
          <div class="table-responsive">
            <table id="table" class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>NIP</th>
                  <th>Nama</th>
                  <th>Bagian</th>
                  <th>Hadir</th>
                  <th>Tidak Hadir</th>
                  <th>Cuti</th>
                </tr>
              </thead>

              <tbody>


                @forelse ($users as $user )
                  <tr>
                    <td>{{ $user->nip }}</td>
                    <td>{{ $user->nama }}</td>
                    <td>{{ $user->seksi }}</td>
                    @php
                      $kehadiran = 0;
                      $a = App\Models\Absen::where('user_id', $user->id)->where(function ($query) {
                        $query->where('status_masuk', 'hadir')->orWhere('status_masuk', 'terlambat');
                      })->get();
                      foreach ($a as $hadir) {
                        if ($hadir->created_at->month == $bulan) {

                          $kehadiran +=  1;
                        }
                      }

                      $tidak_hadir = 0;
                      $a = App\Models\Absen::where('user_id', $user->id)->where(function ($query) {
                        $query->where('status_masuk', 'tidak hadir');
                      })->get();
                      foreach ($a as $hadir) {
                        if ($hadir->created_at->month == $bulan) {
                          $tidak_hadir +=  1;
                        }
                      }

                      $cuti = 0;
                      $a = App\Models\Absen::where('user_id', $user->id)->where(function ($query) {
                        $query->where('status_masuk', 'cuti');
                      })->get();
                      foreach ($a as $hadir) {
                        if ($hadir->created_at->month == $bulan) {
                          $cuti +=  1;
                        }
                      }
                    @endphp
                    <td>{{ $kehadiran }}</td>
                    <td>{{ $tidak_hadir }}</td>
                    <td>{{ $cuti }}</td>

                  </tr>
                @empty

                @endforelse

              </tbody>
            </table>
          </div>
          @endif

        </div>
      </div>
    </div>
  </div>

@endsection

@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.css"/>
@endpush

@push('script')

<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>

  <script>
    $(document).ready( function () {
      $('#table').DataTable( {
        "bSort" : false
      } );
    } );
  </script>
@endpush
