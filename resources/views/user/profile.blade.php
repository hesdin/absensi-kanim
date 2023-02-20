@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="row">
  <div class="col-xl-4 col-lg-4">
     <div class="card">
      <div class="card-header d-flex justify-content-between">
       {{-- <div class="header-title">
        <h4 class="card-title">Foto Profile</h4>
       </div> --}}
      </div>
      <div class="card-body">
      <div class="form-group text-center">

        <div class="profile-img-edit position-relative">
          @if (Auth::user()->foto == 'profile.png' )
          <img src="{{ asset('assets/images/avatars/01.png') }}" alt="profile-pic" class="theme-color-default-img profile-pic rounded-pill avatar-120">
          @else
          <img src="{{ asset('assets/images/profiles/'.Auth::user()->foto ) }}" alt="profile-pic" class="theme-color-default-img profile-pic rounded-pill avatar-120">
          @endif
        </div>

        <div class="img-extension mt-3">
           <div class="d-inline-block align-items-center">
            <span>{{ Auth::user()->nama }}</span>
           </div>
        </div>
       </div>
      </div>
     </div>
  </div>
  <div class="col-xl-8 col-lg-8">
  @if (Session::get('success'))
    <div class="alert alert-success alert-solid alert-dismissible fade show p-2 " role="alert">
      <span>{{ Session::get('success') }}</span>
      <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if (Session::get('fail'))
    <div class="alert alert-danger alert-solid alert-dismissible fade show p-2 " role="alert">
      <span>{{ Session::get('fail') }}</span>
      <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
     <div class="card">
      <div class="card-header d-flex justify-content-between">
       <div class="header-title">
        <h5 class="card-title">Pengaturan Profile</h5>
       </div>
      </div>
      <div class="card-body">
       <div class="new-user-info">
        <form action="{{ route('profileUpdate', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
           <div class="row">

            <div class="form-group col-md-12">
             <label class="form-label">Nama</label>
             <input type="text" class="form-control" name="nama" value="{{ Auth::user()->nama }}">
            </div>
            <div class="form-group col-md-12">
            <label class="form-label">Email</label>
            <input type="text" class="form-control" name="email" value="{{ Auth::user()->email }}">
           </div>
            <div class="form-group col-sm-12">
             <label class="form-label">Jenis Kelamin</label>
             <select name="jk" class="selectpicker form-control" data-style="py-0">
              <option>Select Country</option>
              <option value="Laki-laki" {{ Auth::user()->jk == 'Laki-laki' ? 'selected' : ''}}>Laki-laki</option>
              <option value="Perempuan" {{ Auth::user()->jk == 'Perempuan' ? 'selected' : ''}}>Perempuan</option>
             </select>
            </div>

            <div class="form-group col-md-12">
            <label class="form-label">Foto Profile</label>
            <input type="file" class="form-control" name="foto">
           </div>

           </div>
           <hr>
           <h5 class="mb-3">Ganti Password</h5>
           @if (Session::has('error'))
          <span class="text-danger">{{ Session::get('error') }}</span>
           @endif
           <div class="row">
            <div class="form-group col-md-6">
             <label class="form-label" for="pass">Password Lama</label>
             <input type="password" class="form-control" name="pass">
            </div>
            <div class="form-group col-md-6">
             <label class="form-label" for="rpass">Password Baru</label>
             <input type="password" class="form-control" name="newPass">
            </div>
           </div>
           <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
       </div>
      </div>
     </div>
  </div>
 </div>
@endsection
