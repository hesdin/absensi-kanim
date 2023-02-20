@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    <div class="iq-navbar-header" style="height: 215px;">
        <div class="container-fluid iq-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="flex-wrap d-flex justify-content-between align-items-center">
                        <div class="text-dark">
                            <h4>Hai {{ Auth::user()->nama }}!</h4>
                            <p>Selamat datang di absensi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@push('script')
    <script>
        const hadir = ({{ $hadir }} / {{ cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) }}) * 100
        const terlambat = ({{ $terlambat }} / {{ cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) }}) * 100
        const tidakHadir = ({{ $tidakHadir }} / {{ cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) }}) * 100
        const options = {
            series: [hadir, terlambat, tidakHadir],
            chart: {
                height: 230,
                type: "radialBar",
            },
            colors: ["#1aa053", "#f1da4b", "#c03221"],
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
            labels: ["Hadir", "Terlambat", "Tidak hadir"],
        };
        const chart = new ApexCharts(document.querySelector("#myChart"), options);
        chart.render();
    </script>
@endpush
