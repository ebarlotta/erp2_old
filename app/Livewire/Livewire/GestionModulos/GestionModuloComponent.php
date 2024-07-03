<?php

namespace App\Http\Livewire\GestionModulos;

use Livewire\Component;
use App\Models\Modulo as Modulos;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;


class GestionModuloComponent extends Component
{
    public $name;
    public $modulos;
    public $permisos;
    public $nombre_permiso;
    public $idpermisoaeliminar;

    public $buscar;

    public $modulo_id;

    use WithPagination;


    public function render()
    {
        if ($this->buscar) {
            $this->modulos = Modulos::where('name', 'LIKE', "%" . $this->buscar . "%")->get();

            return view('livewire.modulos.modulo-component',['datos'=> Modulos::where('name', 'LIKE', "%" . $this->buscar . "%")->orderby('name')->paginate(7),])->extends('layouts.adminlte');
        } else {
            $this->modulos = Modulos::where('id','>',1)->get();
            // dd($this->modulos);
            // $this->modulos = Modulos::all();
            return view('livewire.modulos.modulo-component',['datos'=> Modulos::where('id','>',0)->orderby('name')->paginate(7),])->extends('layouts.adminlte');
        }
    }

    public function showNew()
    {
        $this->reset('name');
    }

    public function showNewPermiso() {
        $this->reset('nombre_permiso');
    }

    public function showEdit($id)
    {
        $modulos = Modulos::find($id);
        $this->name = $modulos->name;
        $this->modulo_id = $id;
        
        //Cargar todos los permisos desponibles del módulo
        $this->permisos=Permission::where('name', 'LIKE', '%'. $modulos->name . '%')->get();
    }

    public function showDelete($id)
    {
        $modulos = Modulos::find($id);
        $this->name = $modulos->name;
        $this->modulo_id = $id;
    }

    public function destroy($id)
    {
        Modulos::destroy($this->modulo_id);
        $this->reset('name');
        session()->flash('mensaje', 'Se eliminó el módulo.');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:modulos|max:255',
        ]);
        Modulos::updateOrCreate(['id' => $this->modulo_id], [
            'name' => $this->name,
        ]);
        $this->modulo_id = null;
        session()->flash('mensaje', 'Se guardó el módulo.');
    }

    public function storePermiso() {
        $this->validate([
            'nombre_permiso' => 'required|max:255',
        ]);

        $name = $this->name.'.'.$this->nombre_permiso;
        $permission = Permission::create(['name' => $name]);

        $this->nombre_permiso = null;
        session()->flash('mensaje', 'Se guardó el Permiso.');
        $this->showEdit( $this->modulo_id);
    }

    public function getPermisoaEliminar($id,$nombre_permiso) {
        $this->idpermisoaeliminar = $id;
        $this->nombre_permiso = $nombre_permiso;
    }

    public function destroyPermiso($id)
    {
        Permission::destroy($id);
        $this->reset('name');
        session()->flash('mensaje', 'Se eliminó el permiso.');
        $this->showEdit( $this->modulo_id);
    }
}
