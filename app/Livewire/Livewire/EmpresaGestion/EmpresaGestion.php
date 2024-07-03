<?php

namespace App\Http\Livewire\EmpresaGestion;

use Livewire\Component;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class EmpresaGestion extends Component

{
    use WithFileUploads;
    use WithPagination;

    public $empresas;
    public $isModalOpen;
    public $seleccionado;
    public $empresa;
    public $empresa_id, $name, $direccion, $cuit, $ib, $imagen, $establecimiento, $telefono, $actividad, $actividad1;

    public function render()
    {
        $this->empresas=Empresa::all();
        return view('livewire.empresa-gestion.empresa-gestion',['datos'=> Empresa::orderby('name')->paginate(3),])->extends('layouts.adminlte');
    }

    public function mostrarmodal()
    {
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }
    public function CargarDatosEmpresa($id) {
        $empresa = Empresa::find($id);
        $this->name = $empresa->name; 
        $this->direccion = $empresa->direccion; 
        $this->cuit = $empresa->cuit; 
        $this->ib = $empresa->ib; 
        $this->imagen = $empresa->imagen; 
        $this->establecimiento = $empresa->establecimiento; 
        $this->telefono = $empresa->telefono; 
        $this->actividad = $empresa->actividad; 
        $this->actividad1 = $empresa->actividad1; 
        $this->empresa_id = $id; 

        $this->mostrarmodal();
    }

    public function CrearEmpresa() {
        $this->name = ""; 
        $this->direccion = ""; 
        $this->cuit = ""; 
        $this->ib = ""; 
        $this->imagen = ""; 
        $this->establecimiento = ""; 
        $this->telefono = ""; 
        $this->actividad = ""; 
        $this->actividad1 = ""; 
        $this->empresa_id = null; 

        $this->mostrarmodal();
    }

    public function store() {
        $this->validate([
            'name' => 'required',
            'direccion' => 'required',
            'cuit' => 'required',
            'ib' => 'required',
            'establecimiento' => 'required|integer',
            'telefono' => 'required',
            'actividad' => 'required',
            'actividad1' => 'required',
        ]);
        
        $existe=false;  //Consulta si existe la empresa
        $existe = Empresa::find($this->empresa_id);

        $nombreCompleto = basename($this->imagen) . time().'.jpg';

        $this->empresa_id = Empresa::updateOrCreate(['id' => $this->empresa_id],[
            'name' => $this->name,
            'direccion' => $this->direccion,
            'cuit' => $this->cuit,
            'establecimiento' => $this->establecimiento,
            'ib' => $this->ib,
            // 'image' => $this->imagen,
            'imagen' => $this->imagen->storeAs('storageimages',$nombreCompleto),
            // 'imagen' => $this->imagen->storeAs('images2',$nombreCompleto),
            'telefono' => $this->telefono,
            'actividad' => $this->actividad,
            'actividad1' => $this->actividad1,
        ]);
        //dd($this->imagen);

        if (!$existe) {     //Si no existe la empresa, inicializa los módulos básicos correspondientes
            DB::table('empresa_modulos')->insert(['modulo_id' => '1','empresa_id' => $this->empresa_id->id,]);
            DB::table('empresa_modulos')->insert(['modulo_id' => '2','empresa_id' => $this->empresa_id->id,]);
            DB::table('empresa_modulos')->insert(['modulo_id' => '3','empresa_id' => $this->empresa_id->id,]);
            DB::table('empresa_modulos')->insert(['modulo_id' => '4','empresa_id' => $this->empresa_id->id,]);
            DB::table('empresa_modulos')->insert(['modulo_id' => '5','empresa_id' => $this->empresa_id->id,]);
            DB::table('empresa_modulos')->insert(['modulo_id' => '6','empresa_id' => $this->empresa_id->id,]);
            DB::table('empresa_modulos')->insert(['modulo_id' => '7','empresa_id' => $this->empresa_id->id,]);
            DB::table('empresa_modulos')->insert(['modulo_id' => '14','empresa_id' => $this->empresa_id->id,]);

            DB::table('clientes')->insert(['name' => 'CONSUMIDOR FINAL','cuil'=>'20-000000'.$this->empresa_id->id.'-0','direccion'=>'','email'=>'','telefono'=>'','empresa_id' => $this->empresa_id->id,]);  // Inserta al CONSUMIDOR FINAL como cliente
        }

        $this->closeModalPopover();
    }
}
