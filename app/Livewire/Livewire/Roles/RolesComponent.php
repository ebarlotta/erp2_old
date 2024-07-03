<?php

namespace App\Http\Livewire\Roles;

use App\Models\Modulo;
use Livewire\Component;
use App\Models\Roles;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Commands\CreatePermission;

class RolesComponent extends Component
{
    public $name;
    public $roles;
    public $rol_id;
    public $permisos;
    public $permisoshabilitados;
    public $modulo_name;

    public $modulos, $modulo_seleccionado;

    public $buscar;

    // FALTA AGREGAR ROLES Y PERMISOS POR EMPRESA, NO A NIVEL GENERAL, SINO PARTICULAR
    
    public function render()
    {
        $this->modulos = Modulo::all();
        if ($this->buscar) {
            $this->roles = Roles::where('name', 'LIKE', "%" . $this->buscar . "%")->get();
        } else {
            $this->roles = Roles::orderBy('name','ASC')->get();
        }
        return view('livewire.roles.roles-component')->extends('layouts.adminlte');
    }

    public function showNew()
    {
        $this->reset('name');
    }

    public function showEdit($id)
    {
        $roles = Roles::find($id);
        $this->name = $roles->name;
        $this->rol_id = $id; //Establece el rol
        $this->SeleccionarModulo(1, 'Areas');
        //  dd($this->rol_id);
    }

    public function showDelete($id)
    {
        $roles = Roles::find($id);
        $this->name = $roles->name;
        $this->rol_id = $id;
    }

    public function destroy($id)
    {
        Roles::destroy($this->rol_id);
        $this->reset('name');
        session()->flash('mensaje', 'Se eliminó el rol.');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:roles|max:255',
        ]);
        Roles::updateOrCreate(['id' => $this->rol_id], [
            'name' => $this->name,
        ]);
        $this->rol_id = null;
        session()->flash('mensaje', 'Se guardó el rol.');
    }

    public function SeleccionarModulo($id, $nombreModulo) {
        // dd($nombreModulo);
        unset($this->permisos);
        unset($this->permisoshabilitados);
        if($id == 0) { $this->modulo_seleccionado = null; }
        else { 
            $this->modulo_seleccionado = $id;
            $this->modulo_name = $nombreModulo;

            // $sql = "Select * from permissions where name like '%".$this->modulo_name."%'";

            $sql = "SELECT * FROM permissions where name like '%" . $nombreModulo . "%'";
            // $sql = "SELECT * FROM (Select * from permissions left join role_has_permissions on permissions.id = role_has_permissions.permission_id and role_id<>".$this->rol_id . ") as a where a.role_id is not null and name like '%" . $nombreModulo . "%'";
            // $sql = "SELECT * from (Select * from permissions left join role_has_permissions on permissions.id = role_has_permissions.permission_id where role_id=".$this->rol_id.") as a WHERE a.permission_id is null;";
            // $sql="SELECT * FROM permissions
            // WHERE NOT EXISTS (
            //     SELECT * FROM permissions WHERE name LIKE '%".$this->modulo_name."%')";
            // dd($sql);
            
            unset($this->permisos);
            $this->permisos = db::select($sql);
            // dd($this->permisos);
            $sql = 'SELECT * FROM role_has_permissions join permissions WHERE role_has_permissions.permission_id = permissions.id and role_has_permissions.role_id='.$this->rol_id." and name like '%". $nombreModulo."%'";
            // dd($sql);
            $this->permisoshabilitados = db::select($sql);
        }
    }

    public function AgregarPermiso($idPermiso) {
        $aux = 'SELECT * FROM role_has_permissions WHERE permission_id='.$idPermiso.' and role_id='.$this->rol_id;
        $bux = db::select($aux);
        // dd($bux);
        if(count($bux)) { dd ('Ya dado de alta'); }
        else { 
            $a = 'INSERT INTO role_has_permissions (permission_id, role_id) VALUES ('. $idPermiso.', '.$this->rol_id.')';
            db::select($a);
        }

        //Recarga la información
        $this->SeleccionarModulo($this->modulo_seleccionado,$this->modulo_name);
    }

    public function EliminarPermiso($permision_id, $role_id) {
        $a = 'DELETE FROM role_has_permissions WHERE permission_id = '. $permision_id .' and role_id = '. $role_id;
        db::select($a);

        //Recarga la información
        $this->SeleccionarModulo($this->modulo_seleccionado,$this->modulo_name);
    }
}
