<?php

namespace App\Models;

use Google\Service\Drive\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Roles extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
    ];

    public function Permisos() {
        //Trae el id del usuario y lo almacena en una variable global
        $a = EmpresaUsuario::where('empresa_id','=',session('empresa_id'))
        ->where('user_id','=',Auth()->user()->id)
        ->get('rol_id');
        // ->get();
        session(['rol_id' => $a[0]['rol_id']]);
        
        $r = Roles::find($a[0]['rol_id']);
        $rol = $r->name;
        //Trae el Rol del usuario y lo almacena en una variable global
        //Trae todos los permisos y les coloca falso como valor
        $todos = DB::select('select * from permissions;');
        foreach($todos as $permiso) {
            session([$permiso->name => false ]);
        }
        
        //Busca todos los permisos habilitados para el Rol y los coloca a nivel global
        $sql = 'select roles.name, permissions.name as nombre from roles inner join role_has_permissions inner join permissions where roles.name="' . $rol . '" and roles.id = role_has_permissions.role_id and role_has_permissions.permission_id = permissions.id';
        $a = DB::select($sql);
        foreach($a as $permiso) {
            session([$permiso->nombre => true ]);
        }
    }

    // public function Permisos($rol,$permiso) {
    //     $a = DB::select('select roles.name, permissions.name from roles inner join role_has_permissions inner join permissions where roles.name="' . $rol . '" and roles.id = role_has_permissions.role_id and role_has_permissions.permission_id = permissions.id and permissions.name="'.$permiso.'"');
    //     // dd(isset($a[0]->name));
    //     return isset($a[0]->name);
    //     // $a = $this->hasManyThrough(Roles::class, role  ::class);
    // }
}
