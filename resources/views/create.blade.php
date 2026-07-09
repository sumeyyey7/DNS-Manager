@extends('layouts.app')

@section('content')

<h1>Yeni Domain Ekle</h1>

<form action="/domains" method="POST">
    @csrf

    <div>
        <label>Domain Adı</label><br>
        <input type="text" name="domain_name">
    </div>

    <br>

    <div>
        <label>Açıklama</label><br>
        <textarea name="description"></textarea>
    </div>

    <br>

    <button type="submit">Kaydet</button>
</form>

@endsection