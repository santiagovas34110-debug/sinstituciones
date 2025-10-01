@extends('layouts.app')
@section('content')
 
<h3>Bienvenido {{ Auth::user()->name }}</h3>
@endsection