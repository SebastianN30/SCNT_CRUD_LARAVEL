@extends('layouts.app')

@section('content')
<div class="container">
<form action="{{ url('/empleado/'.$empleado->id)}}" method="POST" enctype="multipart/form-data">
    {{-- El token que estamos recibiendo es @csrf --}}
    @csrf
    {{-- El motodo que estamos excepuando es PATCH --}}
    {{method_field('PATCH')}}
    @include ('empleado.form',['modo'=>'Actualizar']);

</form>
</div>
@endsection
