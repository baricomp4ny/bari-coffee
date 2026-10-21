@extends('layouts.admin')

@section('title', 'Edit menu')

@section('content')
    <div class="admin-head"><div><div class="eyebrow">Menu</div><h1>Edit {{ $menu->name }}</h1></div></div>
    <form method="POST" action="{{ route('admin.menus.update', $menu) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.menus._form')
    </form>
@endsection
