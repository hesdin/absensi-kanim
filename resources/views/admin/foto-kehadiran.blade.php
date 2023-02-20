@if (isset($fotoMasuk))
    <img src="{{ asset('assets/images/absens/'. $fotoMasuk) }}" alt="">
@elseif (isset($fotoPulang))
  <img src="{{ asset('assets/images/absens/'. $fotoPulang) }}" alt="">
@else
  <img src="{{ asset('assets/images/cuti/'. $fotoCuti) }}" alt="">
@endif
