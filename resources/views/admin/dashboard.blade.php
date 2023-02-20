@extends('layouts.app')

@section('title', 'Dashboard')


@section('header')

    <div class="conatiner-fluid content-inner mt-5 py-0">
        <div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="bg-primary text-white rounded p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="text-end">
                                    Absen Masuk
                                    <h2 class="counter" style="visibility: visible;">{{ $masuk }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="bg-info text-white rounded p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="text-end">
                                    Absen Pulang
                                    <h2 class="counter" style="visibility: visible;">{{ $pulang }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="bg-danger text-white rounded p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="text-end">
                                    Izin / Cuti
                                    <h2 class="counter" style="visibility: visible;">{{ $cuti }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    </div>


@endsection

@push('script')
    <script>
        const jumlahKaryawan = ({{ $jumlahKaryawan }} / {{ cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) }}) * 100
        const absenHariIni = ({{ $absenHariIni }} / {{ cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) }}) * 100
        const belumAbsenHariIni = ({{ $belumAbsenHariIni }} /
            {{ cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) }}) * 100
        const options = {
            series: [jumlahKaryawan, absenHariIni, belumAbsenHariIni],
            chart: {
                height: 230,
                type: "radialBar",
            },
            colors: ["#1aa0ff", "#1aa053", "#c03221"],
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 10,
                        size: "40%",
                    },
                    track: {
                        margin: 10,
                        strokeWidth: "50%",
                    },
                    dataLabels: {
                        show: false,
                    },
                },
            },
            labels: ["Jumlah Karyawan", "Sudah Absen", "Belum Absen"],
        };
        const chart = new ApexCharts(document.querySelector("#myChart"), options);
        chart.render();
    </script>
@endpush
