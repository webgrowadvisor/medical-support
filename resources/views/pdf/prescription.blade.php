<h3>Doctor Prescription</h3>
<p>Date: {{ $prescription->prescription_date }}</p>

<hr>

<h4>Medicines</h4>
<ul>
@foreach($prescription->medicines as $med)
    <li>{{ $med }}</li>
@endforeach
</ul>

@if($prescription->notes)
<hr>
<h4>Notes</h4>
<p>{!! $prescription->notes !!}</p>
@endif
