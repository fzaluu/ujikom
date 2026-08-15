

@if($nilai >= 75)
    <p>Nilai Anda A (Sangat Baik)</p>
@elseif($nilai >= 70)
    <p>Nilai Anda B (Baik)</p>
@else
    <p>Nilai Anda C (Cukup)</p>
@endif
