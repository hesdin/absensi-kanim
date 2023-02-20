@php
    if (isset($bulan)) {
      $bulan = $bulan;
    } else {
      $bulan = 1;
    }
@endphp

<table id="table" class="table table-hover table-bordered">
  <thead>
    <tr>
      <th>NIP</th>
      <th>Nama</th>
      <th>Bagian</th>
      <th>Hadir</th>
      <th>Tidak Hadir</th>
      <th>Cuti</th>
    </tr>
  </thead>

  <tbody>


    @forelse ($users as $user )
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
    @empty

    @endforelse

  </tbody>
</table>
