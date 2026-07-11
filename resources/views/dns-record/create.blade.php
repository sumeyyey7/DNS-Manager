@extends('layouts.app')

@section('content')

<h1>DNS Kaydı Ekle</h1>

<form action="/dns-records" method="POST">

    @csrf

    <label>Domain</label>

    <select name="domain_id">

        @foreach($domains as $domain)

            <option value="{{ $domain->id }}">
                {{ $domain->domain_name }}
            </option>

        @endforeach

    </select>

    <br><br>

    <label>Kayıt Türü</label>

    <select name="type">
        <option>A</option>
        <option>AAAA</option>
        <option>CNAME</option>
        <option>MX</option>
        <option>TXT</option>
        <option>NS</option>
    </select>

    <br><br>

    <label>Değer</label>

    <input type="text" name="value">

    <br><br>

    <label>Host</label>

<input type="text" name="host" placeholder="@ veya www">

<br><br>

    <label>TTL</label>

    <input type="number" name="ttl" value="3600">

    <br><br>

    <button type="submit">

        Kaydet

    </button>

</form>

@endsection