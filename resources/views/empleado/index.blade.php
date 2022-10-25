@extends('layouts.app')
@section('content')
<div class="container">

    @if(Session::has('Mensaje'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ Session::get('Mensaje')}}
    </div>
    @endif

<a href="{{ url('empleado/create')}}" class="btn btn-success">Registrar un nuevo empleado</a>
<br>
<br>
<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>ApellidoPaterno</th>
            <th>ApellidoMaterno</th>
            <th>Correo</th>
            <th>Foto</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach( $empleados as $empleado)
        <tr>
            <td>{{ $empleado->id}}</td>
            <td>{{ $empleado->Nombre}}</td>
            <td>{{ $empleado->ApellidoPaterno}}</td>
            <td>{{ $empleado->ApellidoMaterno}}</td>
            <td>{{ $empleado->Correo}}</td>
            <td>
                <img class="img-thumbnail img-fluid" src="{{ asset('storage').'/'.$empleado->Foto}}" width="100" alt="">
                {{ $empleado->Foto}}
            </td>
            <td>
                <a href="{{ url('/empleado/'.$empleado->id.'/edit')}}" class="btn btn-warning">
                    Editar
                </a>

                <form action="{{ url('/empleado/'.$empleado->id ) }}" class="d-inline" method="post">
                    @csrf
                    {{ method_field('DELETE') }}
                    <input type="submit" onclick="return confirm('Deseas Borrar?')"
                    value="Borrar" class="btn btn-danger">
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>

{!! $empleados->links() !!}
</div>
@endsection
