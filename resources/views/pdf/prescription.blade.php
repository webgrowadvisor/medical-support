
<h3>PATIENT INFORMATION</h3>
<p>Name: {{ $prescription->appointment->user->name ?? '' }}</p>
<p>DOB: {{ $prescription->appointment->user->dob ?? '' }}</p>
<p>More: {{ $prescription->appointment->user->other ?? '' }}</p>

<hr>

<h3>VISIT INFORMATION</h3>
<p>Name: {{ $prescription->appointment->doctor->name ?? '' }}</p>
<p>specialization: {{ $prescription->appointment->doctor->specialization ?? '' }}</p>
<p>Id Number: {{ $prescription->appointment->doctor->gst ?? '' }}</p>
<p>Date: {{ $prescription->appointment->appointment_date ?? '' }}</p>
<p>Time: {{ $prescription->appointment->appointment_time ?? '' }}
to {{ $prescription->appointment->appointment_end ?? '' }}</p>

<hr>

<h3>REASONS FOR VISIT</h3>
<p>{{ $prescription->appointment->notes }}</p>

<hr>

<h3>PROVIDER NOTES</h3>
<p>Provider Notes for Patient:</p>
{!! $prescription->appointment->provider_subjective !!}

@if(Auth::guard('admin')->check() || Auth::guard('seller')->check())
<hr>
<p>Provider Notes for Admin:</p>
{!! $prescription->appointment->provider_objective !!}
@endif
{{-- 
<hr>
<p>ASSESSMENT: {!! $prescription->appointment->provider_assessment !!}</p>
<hr>
<p>PLAN: {!! $prescription->appointment->provider_plan !!}</p> 
--}}

<hr>
<h3>Doctor Prescription</h3>
<p>Date: {{ $prescription->prescription_date }}</p>

<hr>
<h4>Medicines</h4>
@if ($prescription->medicines === 'wait')
    {{ 'No Medicine Currently' }}
@else
<ul>
@foreach($prescription->medicines as $med)
    <li>{{ trim($med) }}</li>
@endforeach
</ul>
@endif

@if($prescription->notes)
<hr>
<h4>Prescription Notes</h4>
<p>{!! $prescription->notes !!}</p>
@endif
