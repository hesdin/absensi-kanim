@extends('layouts.app')

@section('title', 'Tabel Absen')

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
                        <h5 class="card-title">Tabel Absen</h5>
                    </div>
                </div>

                <div class="card-body" style="font-size: 14px;">

                    <div class="row justify-content-start mb-2 ps-4">
                        <div class="col-3">
                            <div class="form-group">
                                <select class="form-select form-control form-control-sm" id="month">
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>S
                                </select>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <select class="form-select form-control form-control-sm" id="exampleFormControlSelect1">
                                    <option value="2022">2022</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table id="table" class="table table-hover table-bordered data-table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Ket</th>
                                </tr>
                            </thead>

                            <tbody>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js" integrity="sha512-LGXaggshOkD/at6PFNcp2V2unf9LzFq6LE+sChH7ceMTDP0g2kn6Vxwgg7wkPP7AAtX+lmPqPdxB47A0Nz0cMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    window.setInterval(function () {
        $('#clock').html(moment().format('H:mm:ss'))
    }, 1000);
</script>


    <script>
        $(function () {


        var table = $('.data-table').DataTable({

            processing: true,

            serverSide: true,

            ajax: {

            url: "{{ route('tabel.absen', [Request::segment(2)]) }}",

            data: function (d) {

                    d.month = $('#month').val(),

                    d.search = $('input[type="search"]').val()

                }

            },

            'columns': [

                {
                    data: "created_at",
                    render: function(data){
                        moment.locale('id');
                        var tanggal = moment(data);
                        return (moment(data).isValid()) ? tanggal.format('LL') : "-";
                    }
                },

                {
                    data: "date_masuk",
                    render: function(data){
                        moment.locale('id');
                        return (moment(data).isValid()) ? moment(data).format('LT') : "-";
                    }
                },

                {
                    data: "date_pulang",
                    render: function(data){
                        moment.locale('id');
                        return (moment(data).isValid()) ? moment(data).format('LT') : "-";
                    }
                },

                {data: 'status_masuk', name: 'name'},
            ],

            "columnDefs": [{

                "targets": 3,
                "width": '20%',
                "createdCell": function (td, cellData, rowData, row, col) {
                    if ( cellData === 'hadir' ) {
                        $(td).addClass('text-success font-monospace')
                    } else if ( cellData === 'terlambat' ) {
                        $(td).addClass('text-warning font-monospace')
                    } else {
                        $(td).addClass('text-danger font-monospace')
                    }
                }

            }]

        });



        $('#month').change(function(){

            table.draw();

        });



        });
    </script>
@endpush
