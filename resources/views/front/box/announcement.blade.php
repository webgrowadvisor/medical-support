@if($announcement)
    <h4 class="text-center">{{ $announcement->title }}</h4>
    <div>{!! nl2br($announcement->description) !!}</div>
@endif