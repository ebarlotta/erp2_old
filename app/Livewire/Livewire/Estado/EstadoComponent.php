<?php

namespace App\Http\Livewire\Estado;

use Livewire\Component;
use App\Models\Estado;
use Livewire\WithPagination;


class EstadoComponent extends Component
{
    public $isModalOpen = false;
    public $estado, $estado_id;
    protected $estados;

    public $empresa_id;

    use WithPagination;

    public function render()
    {
        $this->empresa_id=session('empresa_id');
        $this->estados = Estado::where('empresa_id', '=', $this->empresa_id)->paginate(7);
        return view('livewire.estado.estado-component',['estados' => $this->estados])->extends('layouts.adminlte');
        // return view('livewire.estado.estado-component',['datos'=> Estado::where('empresa_ids', $this->empresa_id)->paginate(3),])->extends('layouts.adminlte');
    }
    public function create()
    {
        $this->resetCreateForm();   
        $this->openModalPopover();
        $this->isModalOpen=true;
        return view('livewire.estado.createestado')->with('isModalOpen', $this->isModalOpen)->with('name', $this->name);
    }

    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm(){
        $this->estado_id = '';

        $this->name = '';
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);
        Estado::updateOrCreate(['id' => $this->estado_id], [
            'name' => $this->name,
            'empresa_id' =>$this->empresa_id,
        ]);

        session()->flash('message', $this->estado_id ? 'Estado Actualizado.' : 'Estado Creado.');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $estado = Estado::findOrFail($id);
        $this->id = $id;
        $this->estado_id=$id;
        $this->name = $estado->name;
        
        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        Estado::find($id)->delete();
        session()->flash('message', 'Estado Eliminado.');
    }

}
