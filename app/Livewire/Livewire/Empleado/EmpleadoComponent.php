<?php

namespace App\Http\Livewire\Empleado;

use App\Models\Categoriaprofesional;
use Livewire\Component;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class EmpleadoComponent extends Component
{

    use WithPagination;

    public $isModalOpen = false;
    public $empleado, $empleado_id;
    public $empleados;

    public $name, $domicilio, $cuil, $telefono, $legajo, $dni, $nacimiento, $ingreso, $estadocivil, $tipocontratacion;
    public $regimen, $banco, $nrocuentabanco, $jornalizado, $mensualizado=false, $hora, $unidad, $seccion, $activo, $baja, $categoriaprofesional, $categoriasprofesionales;

    public $empresa_id;

    public $search;
    public $listaactivos=true;

    public function render()
    {
        $this->empresa_id = session('empresa_id');
//dd($this->listaactivos);
        if($this->listaactivos) { $activos=1; } else { $activos='?'; }

        $sql = "Select * from empleados where empresa_id=1 and activo=1 and (name like '%ce%')";
        $datos = DB::select(DB::raw($sql)); 

        return view('livewire.empleado.empleado-component',['datos'=> Empleado::where('empresa_id', $this->empresa_id)
            ->where('activo', $activos)
            ->where('name', 'like', '%'.$this->search.'%')
            ->paginate(8),])->extends('layouts.adminlte');

         
        // else { $activos=0; 
        //     return view('livewire.empleado.empleado-component',['datos'=> Empleado::where('empresa_id', $this->empresa_id)
        //     ->orwhere('name', 'like', '%'.$this->search.'%')
        //     ->paginate(30),])->extends('layouts.adminlte');
        // }
        // ->orwhere('domicilio', 'like', '%'.$this->search.'%')
        // ->orwhere('cuil', 'like', '%'.$this->search.'%')
        // ->orwhere('banco', 'like', '%'.$this->search.'%')
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModalPopover();
        $this->isModalOpen = true;
        return view('livewire.empleado.createempleados')->with('isModalOpen', $this->isModalOpen)->with('name', $this->name);
    }

    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm()
    {
        $this->empleado_id = '';

        $this->name = '';
        $this->domicilio = '';
        $this->cuil = '';
        $this->telefono = '';
        $this->legajo = '';
        $this->dni = '';
        $this->nacimiento = '';
        $this->ingreso = '';
        $this->estadocivil = '';
        $this->tipocontratacion;
        $this->regimen = '';
        $this->banco = '';
        $this->nrocuentabanco = '';
        $this->jornalizado = '';
        $this->mensualizado = '';
        $this->hora = '';
        $this->unidad = '';
        $this->seccion = '';
        $this->activo = '';
        $this->baja = null;
        $this->categoriaprofesional = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'domicilio' => 'required',
            'cuil' => 'required|integer',
            'telefono' => 'required|integer',
            'legajo' => 'required|integer',
            'dni' => 'required|integer',
            'nacimiento' => 'required|date',
            'ingreso' => 'required|date',
            'estadocivil' => 'required',
            'tipocontratacion' => 'required',
            'regimen' => 'required',
            'banco' => 'required',
            'nrocuentabanco' => 'required|integer',
            'jornalizado' => 'required|bool',
            'mensualizado' => 'required|bool',
            'hora' => 'required|bool',
            'unidad' => 'required|bool',
            'seccion' => 'required',
            'activo' => 'required|bool',
            // 'categoriaprofesional' => 'required',
            
        ]);
        
        Empleado::updateOrCreate(['id' => $this->empleado_id], [
            'name' => $this->name,
            'empresa_id' => $this->empresa_id,
            'domicilio' => $this->domicilio,
            'cuil' => $this->cuil,
            'telefono' => $this->telefono,
            'legajo' => $this->legajo,
            'dni' => $this->dni,
            'nacimiento'=> $this->nacimiento,
            'ingreso' => $this->ingreso,
            'estadocivil' => $this->estadocivil,
            'tipocontratacion' => $this->tipocontratacion,
            'regimen' => $this->regimen,
            'banco' => $this->banco,
            'nrocuentabanco' => $this->nrocuentabanco,
            'jornalizado' => $this->jornalizado,
            'mensualizado' => $this->mensualizado,
            'hora' => $this->hora,
            'unidad' => $this->unidad,
            'seccion' => $this->seccion,
            'activo' => $this->activo,
            'baja'=> $this->baja,
            'categoriaprofesional_id'=> $this->categoriaprofesional,
        ]);

        session()->flash('message', $this->empleado_id ? 'Empleado Actualizado.' : 'Empleado Creado.');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        $this->categoriasprofesionales = Categoriaprofesional::where('empresa_id',session('empresa_id'))->get();
        $this->id = $id;
        $this->empleado_id = $id;
        $this->legajo = $empleado->legajo;
        $this->name = $empleado->name;
        $this->domicilio = $empleado->domicilio;
        $this->dni = $empleado->dni;
        $this->cuil = $empleado->cuil;
        $this->nacimiento = Carbon::parse($empleado->nacimiento)->format('Y-m-d');
        $this->ingreso = Carbon::parse($empleado->ingreso)->format('Y-m-d');
        $this->estadocivil = $empleado->estadocivil;
        $this->tipocontratacion = $empleado->tipocontratacion;
        $this->regimen = $empleado->regimen;
        $this->banco = $empleado->banco;
        $this->nrocuentabanco = $empleado->nrocuentabanco;
        $this->estadocivil = $empleado->estadocivil;
        $this->telefono = $empleado->telefono;
        $this->jornalizado = $empleado->jornalizado;
        $this->mensualizado = $empleado->mensualizado;
        $this->hora = $empleado->hora;
        $this->unidad = $empleado->unidad;
        $this->seccion = $empleado->seccion;
        $this->activo = $empleado->activo;
        $this->baja = Carbon::parse($empleado->baja)->format('Y-m-d');
        $this->categoriaprofesional = $empleado->categoriaprofesional->id;
                //dd($empleado->categoriaprofesional->id);
        $this->openModalPopover();
    }

    public function delete($id)
    {
        Empleado::find($id)->delete();
        session()->flash('message', 'Empleado Eliminado.');
    }
}
