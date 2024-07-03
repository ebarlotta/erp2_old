<?php

namespace App\Http\Livewire\Unidad;

use Livewire\Component;
use App\Models\Unidad;

class UnidadComponent extends Component
{
    public $isModalOpen = false;
    public $unidad, $unidad_id;
    public $unidades;

    public $empresa_id;

    public function render()
    {
        $this->empresa_id=session('empresa_id');
        $this->unidades = Unidad::where('empresa_id', $this->empresa_id)->get();
        return view('livewire.unidad.unidad-component',['datos'=> Unidad::where('empresa_id', $this->empresa_id)->paginate(7),])->extends('layouts.adminlte');
    }

    public function create()
    {
        $this->resetCreateForm();   
        $this->openModalPopover();
        $this->isModalOpen=true;
        return view('livewire.unidad.createunidad')->with('isModalOpen', $this->isModalOpen)->with('name', $this->name);
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
        $this->unidad_id = '';

        $this->name = '';
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);
        Unidad::updateOrCreate(['id' => $this->unidad_id], [
            'name' => $this->name,
            'empresa_id' =>$this->empresa_id,
        ]);

        session()->flash('message', $this->unidad_id ? 'Unidad Actualizada.' : 'Unidad Creada.');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $unidad = Unidad::findOrFail($id);
        $this->id = $id;
        $this->unidad_id=$id;
        $this->name = $unidad->name;
        
        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        Unidad::find($id)->delete();
        session()->flash('message', 'Unidad Eliminado.');
    }
}
