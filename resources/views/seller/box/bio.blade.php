<div>
    <h5>Name :</h5><span>{{$user->name ?? '--'}}</span>
    <h5>Email :</h5><span>{{$user->email ?? '--'}}</span>
    <h5>Mobile :</h5><span>{{$user->mobile ?? '--'}}</span>
    <h5>Address :</h5><span>{{$user->address ?? '--'}}</span>
    <h5>DOB :</h5><span>{{$user->dob ?? '--'}}</span>
    <h5>Created :</h5><span>{{$user->created_at ?? '--'}}</span>
    <h5>Image :</h5><span>@if($user->image){!! variantImage($user->image, 60, 60) !!}@else {{ '--' }} @endif</span>
    <h5>Status :</h5><span>{{$user->status ?? '--'}}</span>
</div>