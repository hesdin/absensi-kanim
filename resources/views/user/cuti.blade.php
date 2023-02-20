@extends('layouts.app')

@section('title', 'Permohonan Cuti')

@section('content')
  <div class="row">


    {{-- @if (Session::get('success'))
      <div class="alert alert-success alert-solid alert-dismissible fade show p-2" role="alert">
        <span>{{ Session::get('success') }}</span>
        <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert"
          aria-label="Close"></button>
      </div>
    @endif --}}

    @if (Session::get('fail'))
      <div class="alert alert-danger alert-solid alert-dismissible fade show p-2" role="alert">
        <span>{{ Session::get('fail') }}</span>
        <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert"
          aria-label="Close"></button>
      </div>
    @endif

    @if (!$userHasCuti)
      <div class="col-md-6 ps-0">
        <div id="my_camera"></div>
        <div class="card aos-init aos-animate" data-aos="fade-up" data-aos-delay="800">
          <div class="flex-wrap card-header d-flex justify-content-between align-items-center">
            <div class="header-title">
              <h5 class="card-title">Permohonan Cuti</h5>
            </div>
          </div>
          <div class="card-body px-2 pt-3">
            <form class="form-horizontal" action="{{ route('permohonan.cuti.store', Auth::user()->id) }}" method="POST"
              enctype="multipart/form-data">
              @csrf
              <div class="form-group px-3">
                <label class="form-label">Pilih Cuti</label>
                <select name="jc" class="selectpicker form-control" data-style="py-0">
                  <option selected disabled>Pilih Cuti</option>
                  <option value="Cuti Sakit">Cuti
                    Sakit</option>
                  <option value="Cuti Panjang">Cuti
                    Panjang</option>
                </select>
                @error('jc')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group px-3">
                <label class="form-label" for="date">Cuti Sampai</label>
                <input type="date" name="date" class="form-control" id="date" value="">
                @error('date')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group px-3 mb-1">
                <input type=button class="btn btn-sm btn-danger" value="Foto Permohonan"
                  onClick="take_snapshot()" style="display: block;width: 100%;">
                <input type="hidden" name="image" class="image-tag">
              </div>

              <div class="form-group px-3">
                <button type="submit" class="btn btn-sm btn-primary" style="display: block;width: 100%;">Ajukan
                  Permohonan</button>
              </div>

            </form>
          </div>
        </div>
      </div>

      <div class="col-md-6 p-0">
        <div id="results" class="p-2 pt-2 text-danger h6">Foto permohonan akan muncul disini, jika kurang jelas silahkan
          ulangi !</div>
      </div>
    @elseif ($userHasCuti->cuti->status == true)
      <div class="alert alert-success alert-solid alert-dismissible fade show p-2" role="alert">
        <span>Kamu dalam masa cuti</span>
      </div>
    @elseif ($userHasCuti->cuti->status == false)
      <div class="alert alert-success alert-solid alert-dismissible fade show p-2" role="alert">
        <span>Permohonan cuti kamu sedang diproses, konfirmasi akan dikirim lewat email, jika ditolak permohonan harus di ulang</span>
      </div>
    @endif

  </div>
@endsection

@push('script')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

  @if (!$userHasCuti)
  <script>
    Webcam.set({
      width: 505,
      height: 405,
      image_format: 'jpeg',
      jpeg_quality: 90
    });
    Webcam.attach('#my_camera');

    function take_snapshot() {
      Webcam.snap(function(data_uri) {
        $(".image-tag").val(data_uri);
        document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
      });
    }

    $(document).click(function(event) {
      if (!$(event.target).is("#master, #slave")) {
        $("#slave").hide();
      }
    });
  </script>
  @endif

  <script>
     var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();

        if (dd < 10) {
        dd = '0' + dd;
        }

        if (mm < 10) {
        mm = '0' + mm;
        }

        today = yyyy + '-' + mm + '-' + dd;
        document.getElementById("date").setAttribute("min", today);
  </script>

@endpush
