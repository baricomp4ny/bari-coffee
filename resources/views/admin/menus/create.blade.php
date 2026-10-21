@extends('layouts.admin')

@section('title', 'Tambah menu')

@section('content')
    <div class="admin-head"><div><div class="eyebrow">Menu</div><h1>Tambah menu</h1></div></div>
    <form method="POST" action="{{ route('admin.menus.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.menus._form')
    </form>
@endsection
