<?php

namespace App\Http\Livewire\Tag;

use Livewire\Component;
use App\Models\Tag;

class TagComponent extends Component
{
    public $isModalOpen = false;
    public $tag, $tag_id;
    public $tags;
    public $valor;

    public $empresa_id;

    public function render()
    {
        $this->empresa_id=session('empresa_id');
        $this->tags = Tag::where('empresa_id', $this->empresa_id)->get();
        return view('livewire.tag.tag-component',['datos'=> Tag::where('empresa_id', $this->empresa_id)->orderby('name')->paginate(7),])->extends('layouts.adminlte');
    }

    public function create()
    {
        $this->resetCreateForm();   
        $this->openModalPopover();
        $this->isModalOpen=true;
        return view('livewire.tag.createtag')->with('isModalOpen', $this->isModalOpen);
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
        $this->tag_id = '';

        $this->name = '';
        $this->valor = '';
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);
        Tag::updateOrCreate(['id' => $this->tag_id], [
            'name' => $this->name,
            'valor' => $this->valor,
            'empresa_id' =>$this->empresa_id,
        ]);

        session()->flash('message', $this->tag_id ? 'Etiqueta Actualizada.' : 'Etiqueta Creada.');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $this->id = $id;
        $this->tag_id=$id;
        $this->name = $tag->name;
        $this->valor = $tag->valor;
        
        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        Tag::find($id)->delete();
        session()->flash('message', 'Etiqueta Eliminado.');
    }

}
