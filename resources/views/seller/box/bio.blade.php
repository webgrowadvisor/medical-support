<div>
    <h5>Name : <span>{{$user->name ?? '--'}}</span></h5>
    <h5>Email : <span>{{$user->email ?? '--'}}</span></h5>
    <h5>Mobile : <span>{{$user->mobile ?? '--'}}</span></h5>
    <h5>DOB : <span>{{$user->dob ?? '--'}}</span></h5>
    <h5>Created : <span>{{$user->created_at ?? '--'}}</span></h5>
    <h5>Status : <span>{{$user->status ?? '--'}}</span></h5>
    <h5>Address :</h5><span>{{$user->address ?? '--'}}</span>
    <h5>Other :</h5><span>{{$user->other ?? '--'}}</span>
    <h5>Image :</h5><span>@if($user->image){!! variantImage($user->image, 60, 60) !!}@else {{ '--' }} @endif</span>
</div>