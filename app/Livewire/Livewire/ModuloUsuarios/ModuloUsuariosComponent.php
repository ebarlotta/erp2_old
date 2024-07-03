<?php

namespace App\Http\Livewire\ModuloUsuarios;

use App\Models\Modulo;
use Livewire\Component;
use App\Models\User;
use App\Models\ModuloUsuario;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

// Dispatch::all() => Returns a Collection
// Dispatch::all()->where() => Returns a Collection
// Dispatch::where() => Returns a Query
// Dispatch::where()->get() => Returns a Collection
// Dispatch::where()->get()->where() => Returns a Collection
// You can only invoke "paginate" on a Query, not on a Collection.

class ModuloUsuariosComponent extends Component
{
    public $isModalOpen = false;

    public $name;

    use WithPagination;

    public $usuariosglobales;
    public $usuariosdelmodulo;
    //public $usuariosdelaemp;
    public $usuariosNOmodulo;
    public $modulos;
    public $moduloseleccionado;
    public $seleccionado=1;

    public function render()
    {
        $this->usuariosglobales= User::all();
        //$modulos = Modulo::get()->sortBy('id')->paginate(4);
        //$this->modulos = Modulo::all();
        //$datos = Modulo::paginate(10);
        return view('livewire.modulo-usuarios.modulo-usuarios-component',['datos'=> Modulo::orderby('name')->paginate(3),])->extends('layouts.adminlte')
        ->section('content'); //Enzo
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

    public function CargarUsuarios($id)
    {
        $this->moduloseleccionado = Modulo::find($id);
        $this->seleccionado=$id;
        $this->usuariosdelmodulo = DB::table('users')->distinct()
            ->join('modulo_usuarios', 'users.id', '=', 'modulo_usuarios.user_id')
            ->join('modulos',  'modulos.id', '=', 'modulo_usuarios.modulo_id',)
            ->where('modulos.id', $this->moduloseleccionado->id)
            ->select('users.*', 'modulos.name as modulo')
            ->orderby('users.name')
            ->get();

            //$this->usuariosdelaemp = $this->usuariosdelmodulo;
        $array = json_decode($this->usuariosdelmodulo, true);
        //dd($array);
        $this->usuariosdelmodulo=$array;
        $this->usuariosNOmodulo=User::all();
        // $this->usuariosNOmodulo = DB::table('users')->distinct()
        //     ->join('modulo_usuarios', 'users.id', '=', 'modulo_usuarios.user_id')
        //     ->join('modulos',  'modulos.id', '=', 'modulo_usuarios.modulo_id',)
        //     ->where('modulos.id', '<>',$this->moduloseleccionado->id)
        //     ->where('modulos.name','=',$this->moduloseleccionado->name)
        //     ->select('users.*','modulos.name as modulo')
        //     ->orderby('users.name')
        //     ->get();
    }

    public function AgregarUsuario($user_id)
    {
        ModuloUsuario::create(['modulo_id' => $this->moduloseleccionado->id, 'user_id' => $user_id]);
        $this->closeModalPopover();
        $this->usuarios = User::all();
        $this->CargarUsuarios($this->moduloseleccionado->id);
        return view('livewire.modulo-usuarios.modulo-usuarios-component');
    }

    public function EliminarUsuario($user_id)
    {
        $a = ModuloUsuario::where('modulo_id', "=", $this->moduloseleccionado->id)
            ->where('user_id', "=", $user_id)->delete();
        $this->closeModalPopover();
        $this->usuarios = User::all();
        $this->CargarUsuarios($this->moduloseleccionado->id);
        return view('livewire.modulo-usuarios.modulo-usuarios-component');
    }

}
