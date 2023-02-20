<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
    <style>
      table, th, tr, td {
        border: 1px solid;
      }
    </style>
</head>

<body>

  <h3>Rekap Kehadiran Bulan {{ \Carbon\Carbon::createFromFormat('m', $bulan)->isoFormat('MMMM') }}</h3>

    <table cellpadding="5" cellspacing="0" style="width: 100%">
        <tr>
            <th>NIP</th>
            <th>Nama</th>
            <th>Bagian</th>
            <th>Hadir</th>
            <th>Tidak Hadir</th>
            <th>Cuti</th>
        </tr>
        @foreach ($users as $user)
            <tr>
              <td>{{ $user->nip }}</td>
              <td>{{ $user->nama }}</td>
              <td>{{ $user->seksi }}</td>
              @php
                $kehadiran = 0;
                $a = App\Models\Absen::where('user_id', $user->id)->where(function ($query) {
                  $query->where('status_masuk', 'hadir')->orWhere('status_masuk', 'terlambat');
                })->get();
                foreach ($a as $hadir) {
                  if ($hadir->created_at->month == $bulan) {

                    $kehadiran +=  1;
                  }
                }

                $tidak_hadir = 0;
                $a = App\Models\Absen::where('user_id', $user->id)->where(function ($query) {
                  $query->where('status_masuk', 'tidak hadir');
                })->get();
                foreach ($a as $hadir) {
                  if ($hadir->created_at->month == $bulan) {
                    $tidak_hadir +=  1;
                  }
                }

                $cuti = 0;
                $a = App\Models\Absen::where('user_id', $user->id)->where(function ($query) {
                  $query->where('status_masuk', 'cuti');
                })->get();
                foreach ($a as $hadir) {
                  if ($hadir->created_at->month == $bulan) {
                    $cuti +=  1;
                  }
                }
              @endphp
              <td>{{ $kehadiran }}</td>
              <td>{{ $tidak_hadir }}</td>
              <td>{{ $cuti }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>
