@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Show Client</h1>
    <div>
        <strong>Client ID:</strong>
        {{ $client->clientId }}
    </div>
    <div>
        <strong>Name:</strong>
        {{ $client->client_name }}
    </div>
    <div>
        <strong>Client Group:</strong>
        {{ $client->clientgroup->clientgroup_name }}
    </div>
    <div>
        <strong>Registration No:</strong>
        {{ $client->registration_no }}
    </div>
    <div>
        <strong>Address:</strong>
        {{ $client->address }}
    </div>
    <div>
        <strong>City:</strong>
        {{ $client->city }}
    </div>
    <div>
        <strong>State:</strong>
        {{ $client->state }}
    </div>
    <div>
        <strong>Postcode:</strong>
        {{ $client->postcode }}
    </div>
    <div>
        <strong>Phone 1:</strong>
        {{ $client->phone1 }}
    </div>
    <div>
        <strong>Phone 2:</strong>
        {{ $client->phone2 }}
    </div>
    <div>
        <strong>Status:</strong>
        {{ $client->status }}
    </div>
    <a href="{{ route('clients.index') }}" class="btn btn-primary">Back</a>
</div>
@endsection
