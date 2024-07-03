<?php

namespace App\Http\Livewire\Cart;

use Livewire\Component;
use App\Models\Categoriaproducto;
use App\Models\Producto;
use App\Models\Empresa;
use App\Models\Cart as Carro;
use App\Models\CartProduct;
use Illuminate\Support\Facades\Auth;
// use App\Http\Livewire\Empresa\EmpresaComponent as EmpresaComponent;
// use App\Models\EmpresaUsuario; // Utilizado para redireccionar en el caso de que no esté logueado

class Cart extends Component
{
    public $ModalDetail = false;
    public $Modal_Carrito = false;
    public $ModalDescontar = false;
    public $Item_a_eliminar = 0;
    public $subtotal = 0;
    public $user_id;
    public $D05=false;
    public $D10=false;
    public $D20=false;
    public $D30=false;
    public $D40=false;
    public $D50=false;
    public $D60=false;
    public $valor;

    // public $empresas;   // Utilizado para redireccionar en el caso de que no esté logueado

    public function render()
    {
        // Utilizado para redireccionar en el caso de que no esté logueado
        // if(is_null(session('empresa_id'))) { 
            // $Empresas = EmpresaComponent::login();
            //dd($Empresas);
            // $userid=auth()->user()->id;
            // $empresas_usuario = EmpresaUsuario::where('user_id',$userid)->get();
            // foreach($empresas_usuario as $empresa) {
            //     $this->empresas[] = Empresa::find($empresa->empresa_id);
            // }
            // return redirect('inicio');
            // return view('livewire.empresa.empresa-component');
        // }

        $this->empresa = Empresa::where('id','=',session('empresa_id'))->get();
        $this->categorias = Categoriaproducto::where('empresa_id','=',session('empresa_id'))->orderby('id')->get();
        
        $this->detalles = CartProduct::where('user_id','=',Auth::user()->id)
        ->join('productos','productos.id','=','cart_products.productos_id')->get();

        $this->ofertas_especiales = Producto::where('empresa_id','=',session('empresa_id'))->where('descuento_especial','=',true)->get();
     
        $this->D05 ? $D05 = ' or descuento=5' : $D05 = '';
        $this->D10 ? $D10 = ' or descuento=10' : $D10 = '';
        $this->D20 ? $D20 = ' or descuento=20' : $D20 = '';
        $this->D30 ? $D30 = ' or descuento=30' : $D30 = '';
        $this->D40 ? $D40 = ' or descuento=40' : $D40 = '';
        $this->D50 ? $D50 = ' or descuento=50' : $D50 = '';
        $this->D60 ? $D60 = ' or descuento=60' : $D60 = '';
        $filtro = $D05 . $D10 . $D20 . $D30 . $D40 . $D50 . $D60;
        
        if ($filtro) {
            
            $filtro = str_replace('( or ','( ',' empresa_id = ' . session('empresa_id') . ' and (' . $filtro . ')');

            $this->productos = Producto::whereraw($filtro)->get();
            
            $this->minRangePrice = $this->productos->min('precio_compra');
            $this->maxRangePrice = $this->productos->max('precio_compra');
            return view('livewire.cart.index',['datos'=> Producto::whereraw($filtro)->orderby('categoriaproductos_id')->paginate(18),]);    
            // return view('livewire.cart.index',['datos'=> Producto::whereraw(' empresa_id = ' . session('empresa_id') . ' and (' . $filtro . ')')->orderby('categoriaproductos_id')->paginate(18),]);    
        } else {
            $this->productos = Producto::where('empresa_id','=',session('empresa_id'))->get();
            $this->minRangePrice = $this->productos->min('precio_compra');
            $this->maxRangePrice = $this->productos->max('precio_compra');
            return view('livewire.cart.index',['datos'=> Producto::where('empresa_id','=',session('empresa_id'))->orderby('categoriaproductos_id')->paginate(18),]);
        }

        $this->minRangePrice = $this->productos->min('precio_compra');
        $this->maxRangePrice = $this->productos->max('precio_compra');
     // $this->user_id = Auth::user()->id;
     
        return view('livewire.cart.index',['datos'=> Producto::where('empresa_id','=',session('empresa_id'))->orderby('categoriaproductos_id')->paginate(18),]);
        // return view('livewire.cart.index',['datos'=> Producto::where('empresa_id','=',session('empresa_id'))->orderby('categoriaproductos_id')->paginate(18),]);
    }

    public function single($id) {
        // dd("entro".$id);
        $this->ModalDetail = true;
        $this->producto_detail = Producto::find($id);
        return view('livewire.cart.single');
    }

    public function CloseModal() {
        $this->ModalDetail = false;
    }

    public function CalcularSubtotal() {
        $Acum = 0;
        $this->data = CartProduct::where('user_id','=',Auth::user()->id)
        ->join('productos','productos.id','=','cart_products.productos_id')->get();

        foreach($this->data as $data) {
            if ($data->descuento>0) {
                $result = $data->precio_venta*(1-$data->descuento/100) * $data->cantidad;
            } else {
                $result = $data->precio_venta* $data->cantidad;
            }
            $Acum = $Acum + $result;
        }
        $this->subtotal = $Acum;
    }

    public function show_carrito() {
        $this->Modal_Carrito = true;

        $this->CalcularSubtotal();
    
    }

    public function CloseModal_Carrito() {
        $this->Modal_Carrito = false;
    }

    public function Agregar($id){
        $carrito = Carro::where('user_id', '=', Auth::user()->id)->get();
        
        if (count($carrito)==0) {
            $carrito = new Carro(['user_id' => Auth::user()->id]);
            $carrito->save();
        }

        $detalle = CartProduct::where('user_id','=',Auth::user()->id)
                            ->where('productos_id','=',$id)->get();
        
        if(count($detalle)) {
            $detalle = CartProduct::find($detalle[0]['id']);
            $detalle->cantidad++;
        }
        else {
            $detalle = new CartProduct(['user_id'=>Auth::user()->id,'productos_id'=>$id,'cantidad'=>1]);            
        }
        $detalle->save();
        $this->user_cart=Auth::user()->id;

        $this->show_carrito();
    }

    public function Descontar($id) {
        $descontar = CartProduct::where('productos_id','=',$id)->where('user_id','=',Auth::user()->id)->first();
        if ($descontar->cantidad === 1.0 ) {
            $this->Item_a_eliminar = $descontar->id;
            $this->ModalDescontar = true;
        }
        else {
            $descontar->cantidad--;
            $descontar->save();
            $this->CalcularSubtotal();
        }
    }

    public function CloseModal_Descontar() {
        $this->ModalDescontar = false;
    }

    public function Sacar($id) {
        $descontar = CartProduct::find($id);
        //$descontar = CartProduct::where('productos_id','=',$id)->where('user_id','=',Auth::user()->id)->first();
        //dd($descontar);
        $descontar->destroy($id);
        $this->Item_a_eliminar = 0;
        $this->CloseModal_Descontar();
    }    

    public function ActualizarCantidad($id, $cantidad) {
        $detalle = CartProduct::where('user_id','=',Auth::user()->id)
        ->where('productos_id','=',$id)
        ->get();
        
        if(is_numeric($cantidad)) {
        $cantidad = abs($cantidad);// dd($cantidad);
        
        // $cantidad->validate([
        //     'cantidad' => 'required',
        // ]);
        return $cantidad;
        }
    }

    public function payment_index() {
        //dd('entro');
        //return view('payments');
        //return view('livewire.cart.payment.index');
        // return redirect('payments',compact('id_user'));
 
        return redirect()->to('/payments');
    }

}
