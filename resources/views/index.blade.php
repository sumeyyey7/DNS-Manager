@extends('layouts.app')

@section('content')

<h1>Domainler</h1>

<a href="/domains/create">+ Domain Ekle</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Domain</th>
        <th>Açıklama</th>
    </tr>

    @foreach($domains as $domain)
    <tr>
        <td>{{ $domain->id }}</td>
        <td>{{ $domain->domain_name }}</td>
        <td>{{ $domain->description }}</td>
    </tr>
    @endforeach

</table>

@endsection