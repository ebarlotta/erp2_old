<?php

namespace App\Http\Livewire\EmpresaModulos;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use App\Models\EmpresaModulo;
use App\Models\Modulo;
use App\Models\EmpresaUsuario;
use Livewire\WithPagination;

class EmpresaModulosComponent extends Component
{

    use WithPagination;
    use WithPagination;

    public $isModalOpen = false;

    public $name;

    // public $modulosglobales;
    public $modulosdelaempresa;
    //public $modulosdelaemp;
    public $modulosNOempresa;
    public $empresas;
    public $empresaseleccionada;
    public $seleccionado = 1;

    public function render()
    {
        // if(!isset($this->modulosglobales)) { $this->modulosglobales = Modulo::all(); }
        //$this->empresas = Empresa::all()->sortBy('id');

        $userid=auth()->user()->id;
        $this->empresas= EmpresaUsuario::where('user_id',$userid)
            ->join('empresas','empresas.id','=','empresa_usuarios.empresa_id')
            ->get();
        // dd($this->empresas);

        return view('livewire.empresa-modulos.empresa-modulos-component',['datos'=>EmpresaUsuario::where('user_id',$userid)->join('empresas','empresas.id','=','empresa_usuarios.empresa_id')->paginate(5)])->extends('layouts.adminlte')
        ->section('content'); //enzo
    }
    public function mostrarmodal()
    {
        $this->isModalOpen = true;
    }
    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }

    public function CargarModulos($id)
    {
        $this->empresaseleccionada = Empresa::find($id);
        // dd($this->empresaseleccionada);
        $this->seleccionado = $id;
        $this->modulosdelaempresa = DB::table('modulos')->distinct()
            ->join('empresa_modulos', 'modulos.id', '=', 'empresa_modulos.modulo_id')
            ->join('empresas',  'empresas.id', '=', 'empresa_modulos.empresa_id',)
            ->where('empresas.id', $this->empresaseleccionada->id)
            ->select('modulos.*', 'empresas.name as empresa')
            ->get();
            // $this->modulosdelaemp = $this->modulosdelaempresa;
            // $array = json_decode($this->modulosdelaempresa, true);
            // $this->modulosdelaempresa = $array;
            //$this->modulosglobales = $array;
            // dd($this->modulosdelaempresa);
            //dd($array);
            // dd($this->modulosdelaempresa);
            $this->modulosdelaempresa=json_decode($this->modulosdelaempresa, true);
        $this->modulosNOempresa = Modulo::all();
        //dd($this->modulosNOempresa);

    }

    public function AgregarModulo($modulo_id)
    {
        EmpresaModulo::create(['empresa_id' => $this->empresaseleccionada->id, 'modulo_id' => $modulo_id]);
        $this->closeModalPopover();
        $this->modulos = Modulo::all();
        $this->CargarModulos($this->empresaseleccionada->id);
        return view('livewire.empresa-modulos.empresa-modulos-component');
    }

    public function EliminarModulo($modulo_id)
    {
        $a = EmpresaModulo::where('empresa_id', "=", $this->empresaseleccionada->id)
            ->where('modulo_id', "=", $modulo_id)->delete();
        $this->closeModalPopover();
        $this->modulos = Modulo::all();
        $this->CargarModulos($this->empresaseleccionada->id);
        return view('livewire.empresa-modulos.empresa-modulos-component');
    }
}
