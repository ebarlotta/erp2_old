<?php

namespace App\Http\Livewire\Categoria;

use Livewire\Component;
//use App\Models\CategoriaProducto;
use App\Models\Categoriaproducto;
use Livewire\WithPagination;


class CategoriaproductoComponent extends Component
{

    public $isModalOpen = false;
    public $categoria, $categoria_id;
    protected $categorias;

    public $empresa_id;

    use WithPagination;

    public function render()
    {
        $this->empresa_id=session('empresa_id');
        // $this->categorias = Categoriaproducto::where('empresa_id', $this->empresa_id)->get();
        $this->categorias = Categoriaproducto::where('empresa_id', '=', $this->empresa_id)->paginate(7);

        return view('livewire.categoria.categoriaproducto-component',['categorias'=> $this->categorias])->extends('layouts.adminlte');
        // return view('livewire.categoria.categoriaproducto-component',['categorias'=> Categoriaproducto::where('empresa_id', $this->empresa_id)->paginate(2),])->extends('layouts.adminlte');
    }

    public function create()
    {
        $this->resetCreateForm();   
        $this->openModalPopover();
        $this->isModalOpen=true;
        return view('livewire.categoria.createcategoriaproducto')->with('isModalOpen', $this->isModalOpen)->with('name', $this->name);
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
        $this->categoria_id = '';

        $this->name = '';
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);
        Categoriaproducto::updateOrCreate(['id' => $this->categoria_id], [
            'name' => $this->name,
            'empresa_id' => $this->empresa_id,
        ]);

        session()->flash('message', $this->categoria_id ? 'Categría Actualizada.' : 'Categría Creada.');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $categoria = Categoriaproducto::findOrFail($id);
        $this->id = $id;
        $this->categoria_id=$id;
        $this->name = $categoria->name;
        
        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        Categoriaproducto::find($id)->delete();
        session()->flash('message', 'Categría Eliminada.');
    }
}
