@extends('layouts.app')

@section('title', 'Konfirmasi Kehadiran')

@section('content')
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
            <h5 class="card-title">Konfirmasi Kehadiran</h5>
          </div>
        </div>
        <div class="card-body" style="font-size: 14px;">
          <div class="table-responsive">
            <table id="table" class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>NIP</th>
                  <th>Nama</th>
                  <th>Tanggal</th>
                  <th>Pukul</th>
                  <th>Foto</th>
                  <th>Ket</th>
                  <th>Aksi</th>
                </tr>
              </thead>

              <tbody>

                @forelse ($absens as $absen )
                  <tr>
                    <td>{{ $absen->user->nip }}</td>
                    <td>{{ $absen->user->nama }}</td>
                    <td>{{ Carbon\Carbon::parse($absen->date_masuk)->format('Y/m/d') }}</td>
                    <td>{{ Carbon\Carbon::parse($absen->date_masuk)->format('H:i') }}</td>
                    <td>
                      <a target="_blank" class="btn btn-sm btn-icon text-primary flex-end" data-bs-toggle="tooltip" title="" href="{{ route('admin.foto-kehadiran', $absen->id) }}" data-bs-original-title="Lihat Foto">
                        <span class="btn-inner">
                          <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">                                    <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>                                    <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                </svg>
                        </span>
                      </a>

                    </td>
                    <td>{{ $absen->status_masuk }}</td>

                    <td>

                      <a class="btn btn-sm btn-icon text-success flex-end" data-bs-toggle="tooltip" title="" href="{{ route('admin.terima-kehadiran', ['status' =>'masuk', 'id' => $absen->id]) }}" data-bs-original-title="Terima">
                        <span class="btn-inner">
                          <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">                                    <path d="M15.8325 8.17463L10.109 13.9592L3.59944 9.88767C2.66675 9.30414 2.86077 7.88744 3.91572 7.57893L19.3712 3.05277C20.3373 2.76963 21.2326 3.67283 20.9456 4.642L16.3731 20.0868C16.0598 21.1432 14.6512 21.332 14.0732 20.3953L10.106 13.9602" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                </svg>
                        </span>
                      </a>

                      <a class="btn btn-sm btn-icon text-danger " data-bs-toggle="tooltip" title="" href="{{ route('admin.tolak-kehadiran', ['status' =>'masuk', 'id' => $absen->id]) }}" onclick="event.preventDefault();document.getElementById('tolak-kehadiran').submit();" data-bs-original-title="Tolak">

                        <form action="{{ route('admin.tolak-kehadiran', ['status' =>'masuk', 'id' => $absen->id]) }}" method="POST" class="d-none" id="tolak-kehadiran">@csrf @method('DELETE')
                        </form>
                        
                        <span class="btn-inner">
                          <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">                                    <path d="M14.3955 9.59497L9.60352 14.387" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    <path d="M14.3971 14.3898L9.60107 9.59277" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                </svg>
                        </span>
                      </a>

                    </td>
                  </tr>
                @empty

                @endforelse

              </tbody>
            </table>
          </div>
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
