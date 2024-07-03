<?php

namespace App\Http\Livewire\Producto;

use App\Models\Categoriaproducto;
use App\Models\Estado;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Unidad;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Exception;

class ProductoComponent extends Component
{
    public $isModalOpen = false;

    public $name;

    use WithPagination;

    use WithFileUploads;

    //public $productos;
    public $productos_id, $producto;
    public $seleccionado=1;
    public $empresa_id;
    public $productoseleccionado;
    public $unidades;
    public $ruta;
    public $categoria_productos;
    public $proveedores;
    public $estados;
     

    public function render()
    {
        $this->empresa_id = session('empresa_id');
        //$this->unidades = Unidad::where('empresa_id', $this->empresa_id);
        //$this->productos = Producto::where('empresa_id', $this->empresa_id)->paginate(5);
        return view('livewire.producto.producto-component',['productos' => Producto::where('empresa_id', $this->empresa_id)->paginate(7)])
            ->extends('layouts.adminlte')->with('unidades', Unidad::where('empresa_id', $this->empresa_id)->get());
        
        // $this->unidades = Unidad::where('empresa_id', $this->empresa_id);
        // $this->productos = Producto::where('empresa_id', $this->empresa_id)->paginate(5);
        // return view('livewire.producto.producto-component',compact([$this->unidades,$this->productos]));
    }

    public function mostrarmodal()
    {

        $this->resetCreateForm();
        $this->isModalOpen = true;
        $this->openModalPopover();
    }
    public function openModalPopover()
    {
        $this->unidades = Unidad::where('empresa_id', $this->empresa_id)->get();
        $this->categoria_productos = Categoriaproducto::where('empresa_id', $this->empresa_id)->get();
        $this->estados = Estado::where('empresa_id', $this->empresa_id)->get();
        $this->proveedores = Proveedor::where('empresa_id', $this->empresa_id)->get();
        //dd($this->proveedores);
        $this->isModalOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }   

    private function resetCreateForm()
    {
        $this->producto_id = '';

        $this->name = '';
        $this->descripcion = '';
        $this->precio_compra = '';
        $this->existencia = '';
        $this->stock_minimo = '';
        $this->lote = '';
        $this->ruta = '';
        $this->unidads_id = '';
        $this->categoriaproductos_id = '';
        $this->estados_id = '';
        $this->proveedor_id = '';
        $this->ruta;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'descripcion' => 'required',
            'precio_compra' => 'required|numeric',
            'existencia' => 'required|numeric',
            'stock_minimo' => 'required|numeric',
            'unidads_id' => 'required|integer',
            'categoriaproductos_id' => 'required|integer',
            'estados_id' => 'required|integer',
            'proveedor_id' => 'required',            
        ]);
        //dd($this->store('ruta'));
        //$infoPath = File::extension($this->ruta);
        
        //dd($infoPath);
        //dd($this->ruta);

        if($this->ruta==NULL) {
            $this->ruta = "sin_imagen.jpg";   
        } else
        {
            $nombreCompleto = basename($this->ruta) . time().'.jpg';
            $this->ruta = $nombreCompleto;
        }

        //dd($this->ruta);

        // if (!$this->ruta) {
        //     //$nombreCompleto = basename($this->ruta) . time().'.jpg';       //$this->ruta->extension();
        //     //dd($nombreCompleto);
        //     try { 
        //         //$this->ruta->storeAs('images2', $nombreCompleto);
        //         $this->ruta = $nombreCompleto;
        //     }
        //     catch(Exception $e) {
        //         $this->ruta = $this->ruta;
        //     }
        // }       // CORREGIR

        //dd($this->ruta->file);
        //dd(file($this->ruta));   //  /tmp/phpAFTkwl
        //dd(basename($this->ruta));   //    phpQqaocm
        
        Producto::updateOrCreate(['id' => $this->producto_id], [
            'name' => $this->name,
            'descripcion' => $this->descripcion,
            'precio_compra' => $this->precio_compra,
            'existencia' => $this->existencia,
            'stock_minimo' => $this->stock_minimo,
            'lote' => $this->lote,
            'ruta' => $this->ruta,
            'unidads_id' => $this->unidads_id,
            'categoriaproductos_id' => $this->categoriaproductos_id,
            'estados_id'=> $this->estados_id,
            'empresa_id' => session('empresa_id'),
            'proveedor_id' => $this->proveedor_id,
        ]);

        session()->flash('message', $this->producto_id ? 'Producto Actualizado.' : 'Producto Creado.');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    // public function save()
    // {
    //     $this->validate([
    //         'ruta' => 'image|max:1024', // 1MB Max
    //     ]);
    //     dd($this->ruta);
    // }


    public function edit($id)
    {

        //dd($this->unidades = Unidad::where('empresa_id', $this->empresa_id));
        // $this->unidades = Unidad::where('empresa_id', $this->empresa_id);
        // $this->unidades=json_encode($this->unidades, true);
        $this->productoseleccionado = Producto::findOrFail($id);
        $this->name = $this->productoseleccionado->name;
        $this->descripcion = $this->productoseleccionado->descripcion;
        $this->precio_compra = $this->productoseleccionado->precio_compra;
        $this->existencia = $this->productoseleccionado->existencia;
        $this->stock_minimo = $this->productoseleccionado->stock_minimo;
        $this->lote = $this->productoseleccionado->lote;
        $this->ruta = $this->productoseleccionado->ruta;
        $this->unidads_id = $this->productoseleccionado->unidads_id;
        $this->categoriaproductos_id = $this->productoseleccionado->categoriaproductos_id;
        $this->estados_id = $this->productoseleccionado->estados_id;
        $this->proveedor_id = $this->productoseleccionado->proveedor_id;
        $this->ruta = $this->productoseleccionado->ruta;

        $this->producto_id = $id;

        // $this->productoseleccionado=json_decode($this->productoseleccionado, true);

        $this->openModalPopover();
    }

    public function delete($id)
    {
        Producto::find($id)->delete();
        session()->flash('message', 'Producto Eliminado.');
    }

}
