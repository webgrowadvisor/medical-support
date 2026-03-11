@if($libary)
    <h4 class="text-center">{{ $libary->title }}</h4>
    <div>{!! nl2br($libary->full_content) !!}</div>
@endif