<?php

namespace App\Http\Livewire\Categoriaprofesional;

use App\Models\Categoriaprofesional;
use Livewire\Component;

class CategoriaprofesionalComponent extends Component
{
    public $categorias; 
    public $categoriaseleccionada;
    public $name;
    public $subcategoria;
    public $cct;
    public $preciodia;
    public $preciomes;
    public $preciohora;
    public $preciounidad;
    public $basico   ;
    public $basico1  ;
    public $basico2  ;
    public $porcentaje;
    public $elementosactivos=1;
    public $activo;
    public $observacion;

    public function render()
    {
        if($this->elementosactivos==1) {
            $this->categorias = Categoriaprofesional::where('empresa_id',session('empresa_id'))->where('activo',$this->elementosactivos)->get();
        } else {
            $this->categorias = Categoriaprofesional::where('empresa_id',session('empresa_id'))->get();
        }
        
        return view('livewire.categoriaprofesional.categoriaprofesional-component')->extends('layouts.adminlte');
    }

    public function CargarDatosCategoria($id) {
        $this->categoriaseleccionada=$id;
        $categoria = Categoriaprofesional::find($id);
        $this->id = $categoria->id;
        $this->name = $categoria->name;
        $this->subcategoria = $categoria->subcategoria;
        $this->cct = $categoria->cct;
        $this->preciodia = number_format($categoria->preciodia, 2,'.','');
        $this->preciomes = number_format($categoria->preciomes,2,'.','');
        $this->preciohora = number_format($categoria->preciohora,2,'.','');
        $this->preciounidad = number_format($categoria->preciounidad,2,'.','');
        $this->basico    = number_format($categoria->basico   ,2,'.','');
        $this->basico1   = number_format($categoria->basico1  ,2,'.','');
        $this->basico2   = number_format($categoria->basico2  ,2,'.','');
        $this->porcentaje = number_format($categoria->porcentaje,2,'.','');
        $this->activo = $categoria->activo;
        $this->observacion = $categoria->observacion;
    }

    public function AgregarNuevaCategoria() {
        $new_categoria = new Categoriaprofesional;

        $new_categoria->name = $this->name;;
        $new_categoria->subcategoria = $this->subcategoria;
        $new_categoria->cct = $this->cct;
        $new_categoria->preciodia = $this->preciodia;
        $new_categoria->preciomes = $this->preciomes;
        $new_categoria->preciohora = $this->preciohora;
        $new_categoria->preciounidad = $this->preciounidad;
        $new_categoria->basico = $this->basico;
        $new_categoria->basico1 = $this->basico1;
        $new_categoria->basico2 = $this->basico2;
        $new_categoria->porcentaje = $this->porcentaje;
        $new_categoria->activo = $this->activo;
        $new_categoria->empresa_id = session('empresa_id');
        $new_categoria->observacion = $this->observacion;

        $new_categoria->save();
        $this->categoriaseleccionada = $new_categoria->id;
        $this->render();
    }

    public function ActualizarCategoria() {
        $categoria_original = Categoriaprofesional::find($this->categoriaseleccionada);
        $categoria_original->activo = false;
        $categoria_original->save();

        $this->AgregarNuevaCategoria();
    }

    public function ModificarCategoria() {

        $modify_categoria = Categoriaprofesional::find($this->categoriaseleccionada);
        $modify_categoria->name = $this->name;;
        $modify_categoria->subcategoria = $this->subcategoria;
        $modify_categoria->cct = $this->cct;
        $modify_categoria->preciodia = $this->preciodia;
        $modify_categoria->preciomes = $this->preciomes;
        $modify_categoria->preciohora = $this->preciohora;
        $modify_categoria->preciounidad = $this->preciounidad;
        $modify_categoria->basico = $this->basico;
        $modify_categoria->basico1 = $this->basico1;
        $modify_categoria->basico2 = $this->basico2;
        $modify_categoria->porcentaje = $this->porcentaje;
        $modify_categoria->activo = $this->activo;
        //$modify_categoria->empresa_id = session('empresa_id');
        $modify_categoria->observacion = $this->observacion;
        $modify_categoria->save();

        $this->render();
    }

}
