<?php

namespace App\Http\Livewire\Tablas;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Tabla;

class EditarTablaComponent extends Component
{
    public $ModalOk;
    public $user_id;
    public $isModalOpen = false;
    public $ListadeTablas;
    
    public function render()
    {
        if(is_null($this->user_id)) $this->user_id = 0;
        $this->ListadeTablas = Tabla::groupBy('name')->get(['name','id']);
        // $this->ListadeTablas = Tabla::distinct(['name'])->get(['name','id']);
        // $this->ListadeTablas = Tabla::select('id')->select('name')->distinct()->get();
        // $this->ListadeTablas = Tabla::selectraw(' names, (select id from tabla_usuarios WHERE tabla_usuarios.tabla_id = tablas.id and tabla_usuarios.user_id = '.Auth::user()->id.') as relac_id, empresa_id, id as tabla_id')
        // ->where('id','>=',1)
        // ->where('empresa_id','=',session('empresa_id'))
        // ->get();
        // dd($this->ListadeTablas);
        return view('livewire.tablas.editar-tabla-component',['datos'=> $this->ListadeTablas])->extends('layouts.adminlte');
    }

    public function edit($nameTabla) {
        // dd('En construcción');
        // $tabla = Tabla::where('id','=',$idTabla)
        $tabla = Tabla::where('name','=',$nameTabla)
        ->where('empresa_id','=',session('empresa_id'))
        ->get();
        if(count($tabla)) {
            session()->flash('message', 'La Tabla ya se encuentra Activa para esta empresa');
        } else {
            // $tabla = Tabla::find($nameTabla);
            $tablaNueva = new Tabla();
            // $tablaNueva->name = $tabla->name;
            // $tablaNueva->encabezadocolumna = $tabla->encabezadocolumna;
            // $tablaNueva->ordenarporcampo = $tabla->ordenarporcampo;
            // $tablaNueva->cantidadfila = $tabla->cantidadfila;
            // $tablaNueva->cantidadcolumna = $tabla->cantidadcolumna;
            $tablaNueva->name = $nameTabla;
            $tablaNueva->encabezadocolumna = 'columna';
            $tablaNueva->ordenarporcampo = 'pepe';
            $tablaNueva->cantidadfila = 3;
            $tablaNueva->cantidadcolumna = 2;
            $tablaNueva->empresa_id = session('empresa_id');
            $tablaNueva->save();
            session()->flash('message', 'Tabla relacionada a esta empresa correctamente');
        }

        // dd('En construcción Edit');
    }
    
    public function delete($idTabla) {
        $tabla = Tabla::where('id','=',$idTabla)
        ->where('empresa_id','=',session('empresa_id'))
        ->get();
        if(count($tabla)) {
            $tabla->destroy();
            session()->flash('message', 'La Tabla se encuentra Desactivó');
        } else {
            session()->flash('message', 'Esta Tabla no estaba activa para esta empresa.');
        }
    }
    
    public function create() {
        $this->isModalOpen = !$this->isModalOpen;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
        $this->CargarInformesHabilitados($this->user_id);
    }
}
