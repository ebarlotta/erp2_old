<?php

namespace App\Http\Livewire\Cuenta;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cuenta;

class CuentaComponent extends Component
{

    use WithPagination;

    public $isModalOpen = false;
    public $cuenta, $cuenta_id;
    public $cuentas;
    public $name;
    public $empresa_id;

    public function render()
    {
        $this->empresa_id=session('empresa_id');
        $this->cuentas = Cuenta::where('empresa_id', $this->empresa_id)->orderby('name')->get();
        return view('livewire.cuenta.cuenta-component',['datos'=> Cuenta::where('empresa_id', $this->empresa_id)->orderby('name')->paginate(7),])->extends('layouts.adminlte');
    }

    public function create()
    {
        $this->resetCreateForm();   
        $this->openModalPopover();
        $this->isModalOpen=true;
        return view('livewire.cuenta.createcuentas')->with('isModalOpen', $this->isModalOpen)->with('name', $this->name);
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
        $this->cuenta_id = '';
        $this->name = '';
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);
        Cuenta::updateOrCreate(['id' => $this->cuenta_id], [
            'name' => $this->name,
            'empresa_id' => $this->empresa_id,
        ]);

        session()->flash('message', $this->cuenta_id ? 'Cuenta Actualizada.' : 'Cuenta Creada.');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $cuenta = Cuenta::findOrFail($id);
        $this->id = $id;
        $this->cuenta_id=$id;
        $this->name = $cuenta->name;
        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        Cuenta::find($id)->delete();
        session()->flash('message', 'Cuenta Eliminada.');
    }

}
