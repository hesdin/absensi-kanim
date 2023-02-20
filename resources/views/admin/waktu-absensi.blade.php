@extends('layouts.app')

@section('title', 'Waktu Absensi')

@section('content')
<div class="row">
    <div class="col-sm-12">
       <div class="card">
          <div class="card-header d-flex justify-content-between">
             <div class="header-title">
                <h5 class="card-title">Waktu Jam Kerja</h5>
             </div>
          </div>
          <div class="card-body p-0" style="font-size: 14px;">
             <div class="table-responsive mt-4">
                <table id="basic-table" class="table mb-0" role="grid">
                   <thead>
                      <tr>
                         <th>Keterangan</th>
                         <th>Jam Mulai</th>
                         <th>Jam Selesai</th>
                         <th>Aksi</th>
                      </tr>
                   </thead>
                   <tbody>
                    <tr>
                        <td>
                           <div class="d-flex align-items-center">
                              {{-- <img class="rounded img-fluid avatar-40 me-3 bg-soft-primary" src="../../assets/images/shapes/01.png" alt="profile"> --}}
                              <h6 style="font-size: 14px;">{{ strtoupper($masuk->ket) }}</h6>
                           </div>
                        </td>
                        <td><div class="text-info fw-bold">{{ $masuk->mulai }}</div></td>
                        <td><div class="text-danger fw-bold">{{ $masuk->selesai }}</div></td>
                        <td>
                           <a class="btn btn-sm btn-icon text-primary flex-end" data-bs-toggle="modal" data-bs-target="#editMasuk">
                               <span class="btn-inner">
                                   <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                       <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                       <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                   </svg>
                               </span>
                           </a>
                         </td>
                    </tr>

                    <tr>
                        <td>
                           <div class="d-flex align-items-center">
                              {{-- <img class="rounded img-fluid avatar-40 me-3 bg-soft-primary" src="../../assets/images/shapes/01.png" alt="profile"> --}}
                              <h6 style="font-size: 14px;">{{ strtoupper($pulang->ket) }}</h6>
                           </div>
                        </td>
                        <td><div class="text-info fw-bold">{{ $pulang->mulai }}</div></td>
                        <td><div class="text-danger fw-bold">{{ $pulang->selesai }}</div></td>
                        <td>
                           <a class="btn btn-sm btn-icon text-primary flex-end" data-bs-toggle="modal" data-bs-target="#editPulang">
                               <span class="btn-inner">
                                   <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                       <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                       <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                   </svg>
                               </span>
                           </a>
                         </td>
                    </tr>
                   </tbody>
                </table>
             </div>
          </div>
       </div>
    </div>
 </div>

<div class="modal fade" id="editMasuk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Jam Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('admin.update-waktu-absensi', $masuk->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label" for="exampleInputtime">Jam Mulai</label>
                        <input type="time" name="mulai" class="form-control" id="exampleInputtime" value="{{ $masuk->mulai }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="exampleInputtime">Jam Selesai</label>
                        <input type="time" name="selesai" class="form-control" id="exampleInputtime" value="{{ $masuk->selesai }}">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editPulang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Jam Pulang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.update-waktu-absensi', $pulang->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label" for="exampleInputtime">Jam Mulai</label>
                            <input type="time" name="mulai" class="form-control" id="exampleInputtime" value="{{ $pulang->mulai }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="exampleInputtime">Jam Selesai</label>
                            <input type="time" name="selesai" class="form-control" id="exampleInputtime" value="{{ $pulang->selesai }}">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                </div>
                </form>
        </div>
    </div>
</div>
@endsection


