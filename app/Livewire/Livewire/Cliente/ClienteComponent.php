<?php

namespace App\Http\Livewire\Cliente;

use Livewire\Component;
use App\Models\Cliente;
use Livewire\WithPagination;

class ClienteComponent extends Component
{
    public $isModalOpen = false;
    public $cliente, $cliente_id;
    protected $clientes;

    public $name;
    public $direccion;
    public $cuil;
    public $telefono;
    public $email;

    public $search;

    public $empresa_id;

    use WithPagination;

    public function render()
    {
        $this->empresa_id = session('empresa_id');
        if($this->search) {
            $this->resetPage();
            $this->clientes = Cliente::where('empresa_id', $this->empresa_id)
            ->where('cuil', 'like', '%'.$this->search.'%')
            ->orderBy('name','asc')
            ->paginate(7);
            // dd($this->clientes);
        }

        else {
            $this->clientes = Cliente::where('empresa_id', $this->empresa_id)
            ->orderBy('name','asc')
            ->paginate(7);
        }

        return view('livewire.cliente.cliente-component',['clientes'=> $this->clientes])->extends('layouts.adminlte');
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModalPopover();
        $this->isModalOpen = true;
        return view('livewire.cliente.createclientes')->with('isModalOpen', $this->isModalOpen)->with('name', $this->name);
    }

    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm()
    {
        $this->cliente_id = '';

        $this->name = '';
        $this->direccion = '';
        $this->cuil = '';
        $this->telefono = '';
        $this->email = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'direccion' => 'required',
            'cuil' => 'required',
            'telefono' => 'required|integer',
            'email' => 'email',
        ]);
        $a=Cliente::updateOrCreate(['id' => $this->cliente_id], [
            'name' => $this->name,
            'empresa_id' => $this->empresa_id,
            'direccion' => $this->direccion,
            'cuil' => $this->cuil,
            'telefono' => $this->telefono,
            'email' => $this->email,
        ]);

        session()->flash('message', $this->cliente_id ? 'Cliente Actualizado.' : 'Cliente Creado.');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $this->id = $id;
        $this->cliente_id = $id;
        $this->name = $cliente->name;
        $this->direccion = $cliente->direccion;
        $this->cuil = $cliente->cuil;
        $this->telefono = $cliente->telefono;
        $this->email = $cliente->email;

        $this->openModalPopover();
    }

    public function delete($id)
    {
        Cliente::find($id)->delete();
        session()->flash('message', 'Cliente Eliminado.');
    }
}
