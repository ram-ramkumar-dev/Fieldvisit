@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Show Client Group</h1>
    <div>
        <strong>Name:</strong>
        {{ $clientgroup->clientgroup_name }}
    </div>
    <div>
        <strong>Description:</strong>
        {{ $clientgroup->clientgroup_desc }}
    </div>
    <a href="{{ route('clientgroups.index') }}" class="btn btn-primary">Back</a>
</div>
@endsection
