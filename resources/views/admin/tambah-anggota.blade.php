@extends('layouts.app')

@section('title', 'Tambah Anggota')

@section('content')
    <div class="row">
        <div class="col-xl-9 col-lg-9">
            @if (Session::get('success'))
                <div class="alert alert-success alert-solid alert-dismissible fade show p-2 " role="alert">
                    <span>{{ Session::get('success') }}</span>
                    <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
            @endif

            @if (Session::get('fail'))
                <div class="alert alert-danger alert-solid alert-dismissible fade show p-2 " role="alert">
                    <span>{{ Session::get('fail') }}</span>
                    <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h5 class="card-title">Tambah Anggota</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="new-user-info">
                        <form action="{{ route('admin.store-anggota') }}" method="POST">
                            @csrf

                            <div class="row">

                                <div class="form-group col-md-12">
                                    <label class="form-label">Nomor Induk Pegawai</label>
                                    <input required value="{{ old('nip') }}" type="text" class="form-control"
                                        name="nip" minlength="18" maxlength="18">
                                    @error('nip')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-label">Nama</label>
                                    <input required value="{{ old('nama') }}" type="text" class="form-control"
                                        name="nama">
                                    @error('nama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jk" class="selectpicker form-control form-select" data-style="py-0">
                                        <option selected disabled>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    @error('jk')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-label">Seksi Bagian</label>
                                    <input required value="{{ old('seksi') }}" type="text" class="form-control"
                                        name="seksi">
                                    @error('seksi')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-label">Jabatan</label>
                                    <input required value="{{ old('jabatan') }}" type="text" class="form-control"
                                        name="jabatan">
                                    @error('jabatan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-label">Email</label>
                                    <input required value="{{ old('email') }}" type="email" class="form-control"
                                        name="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="form-label" for="pass">Password</label>
                                <input required type="password" class="form-control" name="pass">
                                @error('pass')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
