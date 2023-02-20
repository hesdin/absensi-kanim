@extends('layouts.app')

@section('title', 'Tabel Kehadiran')

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
            <h5 class="card-title">Tabel Kehadiran</h5>
          </div>
        </div>
        <div class="card-body" style="font-size: 14px;">
          <div class="table-responsive">
            <table id="table" class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>NIP</th>
                  <th>Nama</th>
                  <th>Aksi</th>
                </tr>
              </thead>

              <tbody>

                @forelse ($users as $user )
                  <tr>
                    <td>{{ $user->nip }}</td>
                    <td>{{ $user->nama }}</td>

                    <td>

                      <a class="btn btn-sm btn-icon text-primary flex-end" data-bs-toggle="tooltip" title="" href="{{ route('admin.detail-kehadiran', $user->id) }}" data-bs-original-title="Detail">
                        <span class="btn-inner">
                          <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">                                    <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>                                    <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>                                </svg>
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
