@extends('layouts.app')

@section('title', 'Absen Harian')

@section('content')
    <div class="row">
        @if (Session::get('success'))
            <div class="alert alert-success alert-solid alert-dismissible fade show p-2" role="alert">
                <span>{{ Session::get('success') }}</span>
                <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif
    </div>

    @if ($jadwal_masuk_mulai === false)
        <div class="row">
            <div class="alert alert-danger alert-solid alert-dismissible fade show p-2" role="alert">
                <span>Waktu absen masuk belum dibuka</span>
                <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if ($check === null || $check2 === false)
        <div class="row">
            @if (Session::get('fail'))
                <div class="alert alert-danger alert-solid alert-dismissible fade show p-2" role="alert">
                    <span>{{ Session::get('fail') }}</span>
                    <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
            @endif

            @if ($jadwal_masuk_mulai === true)
                <div class="col-md-6 ps-0">
                    <div id="my_camera"></div>
                    <div class="card aos-init aos-animate" data-aos="fade-up" data-aos-delay="800">
                        <div class="flex-wrap card-header d-flex justify-content-between align-items-center">
                            <div class="header-title">
                                <h5 class="card-title">Absensi Masuk</h5>

                            </div>
                            <div class="d-flex align-items-center align-self-center">
                                <div class="d-flex align-items-center text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                        </g>
                                    </svg>
                                    <div class="ms-2">
                                        <span class="text-primary" id="clock"></span>

                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-primary">

                                    <div class="ms-2">
                                        <span class="text-primary">{{ $waktu->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="card-body px-2 pt-3">
                            <form class="form-horizontal" action="{{ route('absen.store', Auth::user()->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group px-3 mb-1">
                                    <input type=button class="btn btn-sm btn-danger" value="Ambil Foto"
                                        onClick="take_snapshot()" style="display: block;width: 100%;">
                                    <input type="hidden" name="image" class="image-tag">
                                </div>

                                <div class="form-group px-3">
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        style="display: block;width: 100%;">Absen Masuk</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 p-0">
                    <div id="results" class="p-2 pt-2 text-danger h6">Foto akan muncul disini, jika tidak sesuai silahkan
                        ulangi !</div>
                </div>
            @endif
        </div>
    @else
        <div class="row">
            @if (Session::get('fail'))
                <div class="alert alert-danger alert-solid alert-dismissible fade show p-2" role="alert">
                    <span>{{ Session::get('fail') }}</span>
                    <button type="button" class="btn-close btn-close-white btn-sm pb-2" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
            @endif



            <div class="{{ !isset($check->status_pulang) ? 'col-md-6' : 'col-md-12' }} ps-0">
                @if ($jadwal_pulang_mulai === false)
                    <div id="my_camera"></div>
                    <div class="alert alert-danger alert-solid alert-dismissible fade show p-2" role="alert">
                        <span>Absen pulang belum dibuka</span>
                    </div>
                @endif
                @if ($check->status_pulang === null && $jadwal_pulang_mulai === true)
                    <div id="my_camera"></div>

                    <div class="card aos-init aos-animate" data-aos="fade-up" data-aos-delay="800">
                        <div class="flex-wrap card-header d-flex justify-content-between align-items-center">

                            <div class="header-title">
                                <h5 class="card-title">Absensi Pulang</h5>
                            </div>
                            <div class="d-flex align-items-center align-self-center">
                                <div class="d-flex align-items-center text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <g>
                                            <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                        </g>
                                    </svg>
                                    <div class="ms-2">
                                        <span class="text-primary" id="clock"></span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-primary">

                                    <div class="ms-2">
                                        <span class="text-primary">{{ $waktu->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="card-body px-2 pt-3">
                            <form class="form-horizontal" action="{{ route('absen.update', Auth::user()->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group px-3 mb-1">
                                    <input type=button class="btn btn-sm btn-danger" value="Ambil Foto"
                                        onClick="take_snapshot()" style="display: block;width: 100%;">
                                    <input type="hidden" name="image" class="image-tag">
                                </div>

                                <div class="form-group px-3">
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        style="display: block;width: 100%;">Absen Pulang</button>
                                </div>

                            </form>
                        </div>
                    </div>
                @else
                    @if ($check->status_pulang !== null)
                        <div class="alert alert-danger alert-solid alert-dismissible fade show p-2" role="alert">
                            <span>Hari ini anda sudah selesai absen, silahkan kembali besok</span>
                        </div>
                    @endif

                @endif

            </div>

            @if (!isset($check->status_pulang))
                <div class="col-md-6 p-0">
                    <div id="results" class="p-2 pt-2 text-danger h6">Foto akan muncul disini, jika belum sesuai silahkan
                        ulangi
                        !</div>
                    {{-- @foreach ($tests as $test)
                <option value="">{{ $test->created_at }}</option>
            @endforeach --}}
                </div>
            @endif
        </div>

    @endif


@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"
        integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        window.setInterval(function() {
            $('#clock').html(moment().format('H:mm:ss'))
        }, 1000);
    </script>

    @if (!isset($check->status_pulang))
        @if ($jadwal_masuk_mulai === true)
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
    @endif
@endpush
