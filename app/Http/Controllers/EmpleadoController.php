<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos['empleados']=Empleado::paginate(1);
        return view('empleado.index',$datos );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('empleado.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
            'Foto'=>'required|max:1000|mimes:jpeg,png,jpg',
        ];
        $Mensaje=[
            'required'=>'El :attribute es necesario',
            'Foto.required'=>'La foto es necesaria'
        ];

        $this->validate($request, $campos, $Mensaje);
        //Variable donde se guardaran los datos del empleado $datosEmpleado
        //La insercion genera un token de tipo json y haremos una excepcion con el siguiente script
        //->except('_token');
        $datosEmpleado = request()->except('_token');

        //Generamos una restriccion de fotografia y lo vamos a alterar
        //Para poderlo insertar en public, uploads
        if($request->hasFile('Foto')){
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads','public');
        }

        Empleado::insert($datosEmpleado);
        return redirect('empleado')->with('Mensaje','Empleado agregado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    /* Funcion para recuperar el id y guardar la informacion del registro que vamos a editar */
    public function edit($id)
    {
        $empleado=Empleado::findOrFail($id);
        return view('empleado.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
        ];
        $Mensaje=[
            'required'=>'El :attribute es necesario',
        ];

        if($request->hasFile('Foto')){
            $campos=['Foto'=>'required|max:1000|mimes:jpeg,png,jpg',];
            $Mensaje=['Foto.required'=>'La foto es necesaria',];
        }

        $this->validate($request, $campos, $Mensaje);

        $datosEmpleado = request()->except(['_token','_method']);

        if($request->hasFile('Foto')){
            $empleado=Empleado::findOrFail($id);
            Storage::delete('public/'.$empleado->Foto);
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads','public');
        }
        Empleado::where('id','=',$id)->update($datosEmpleado);
        $empleado=Empleado::findOrFail($id);
        /* return view('empleado.edit', compact('empleado')); */
        return redirect('empleado')->with('Mensaje','Empleado Modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $empleado=Empleado::findOrFail($id);

        if(Storage::delete('public/'.$empleado->Foto)){
            Empleado::destroy($id);
        }

        return redirect('empleado')->with('Mensaje','Empleado Borrado');
    }
}
