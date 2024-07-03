<?php

namespace App\Http\Livewire\Tablas;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\EmpresaUsuario;
use App\Models\Tabla;
use App\Models\TablaUsuario;
use App\Models\User;
use Livewire\WithPagination;

class TablasComponent extends Component
{

    public $isModalOpen = false;
    public $tablas = [];
    public $user_id;
    public $ModalOk;
    public $tabla_id;
    public $rel_id;
    public $empresa_id;
    public $users;
    public $ListadeTablas;
    use WithPagination;


    public function render()
    {
        if(is_null($this->user_id)) $this->user_id = 0;
        $this->empresa_id=session('empresa_id');
        $this->users = User::join('empresa_usuarios', 'empresa_usuarios.user_id','=', 'users.id')
        ->where('empresa_usuarios.empresa_id','=',$this->empresa_id)->get();
        // dd($this->users);
        return view('livewire.tablas.tablas-component',['datos'=> Tabla::where('empresa_id', $this->empresa_id)->paginate(4),])->extends('layouts.adminlte');
    }

    public function CargarInformesHabilitados($usuario_id) {
        
        $this->tablas = TablaUsuario::join('tablas', 'tabla_usuarios.tabla_id','=', 'tablas.id')
        ->where('tablas.empresa_id','=',session('empresa_id'))
        // ->where('tablas.empresa_id','=',$this->empresa_id)
        ->where('tabla_usuarios.user_id','=',$usuario_id)
        // ->where('tabla_usuarios.user_id','=',Auth::user()->id)
        ->get();
        // dd($this->tablas);
        $this->user_id = $usuario_id;   // Establece el ide de ususario con el que se va a trabajar
        session(['AsignacionOk'=>null]); // Borra cartel
    }

    public function CargarInformesConEstado($user_id) {
        $this->ListadeTablas = Tabla::selectraw(' name, (select id from tabla_usuarios WHERE tabla_usuarios.tabla_id = tablas.id and tabla_usuarios.user_id = '.$user_id.') as relac_id, empresa_id, id as tabla_id')
        ->where('id','>=',1)
        ->where('empresa_id','=',$this->empresa_id)
        ->get();

        // select name, (select tabla_id from tabla_usuarios WHERE tabla_usuarios.tabla_id = tablas.id) as valor, empresa_id from `tablas` where `id` >= 1;
    }

    public function create($user_id)
    {
        // dd('por entrar');
        // return redirect('livewire.tablas.vista')->extends('layouts.adminlte');

        $this->openModalPopover();
        $this->isModalOpen=true;
        $this->CargarInformesConEstado($user_id);
    }

    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
        $this->CargarInformesHabilitados($this->user_id);
    }
    
    public function ModalOkAsignar($relac_id,$tabla_id)
    {
        $this->relac_id = $relac_id;
        $this->tabla_id = $tabla_id;
        $this->ModalOk = !$this->ModalOk;
    }

    public function AsignarInforme() {

//        $condiciones = [['user_id', '=', $this->user_id],['tabla_ifind($this->rel_id;

        $a = TablaUsuario::find($this->relac_id);
            
        

        //$a=TablaUsuario::where('user_id',$this->user_id)->where('tabla_id',$this->tabla_id);
        //dd($a);
        if($a) {
            $a = TablaUsuario::destroy($this->relac_id);
            session(['AsignacionOk'=>'Se eliminó correctamente']);
        } else {
            $a = TablaUsuario::create(['user_id'=>$this->user_id,'tabla_id'=>$this->tabla_id]);
            if($a) session(['AsignacionOk'=>'Se asignó correctamente']);
        }
        $this->CargarInformesHabilitados($this->user_id);
    }
}
