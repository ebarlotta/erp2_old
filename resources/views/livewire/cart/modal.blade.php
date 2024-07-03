<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
   <div class="flex items-end justify-center mt-24 pt-4 px-4 pb-20 text-center sm:block sm:p-0" style="background-color: transparent; ">
       <div class="fixed inset-0 transition-opacity">
           <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
       </div>

       <span class="hidden sm:inline-block sm:align-middle "></span>
       <div class="inline-block align-center bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-1 sm:align-top sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: 10px;" wire:click="CloseModal();">
                   <span aria-hidden="true">&times;</span>
               </button>
           <div class="product-men">
               <div class="men-thumb-item col-md-6">
                   <img src="cart/images/m1.jpg" alt="">
                   <span class="product-new-top" style="right: 23%;">{{ __("labels.New")}}</span>
               </div>
               <div class="men-thumb-item col-md-6">
                   detail
               </div>
               <div class="item-info-product col-md-12">
                   <h4>
                       {{ substr($producto_detail->name,0,20) }}
                   </h4>
                   <div class="info-product-price">
                       <span class="item_price">$ {{ $producto_detail->precio_compra }}</span>
                       <del>${{ $producto_detail->precio_compra * 1.10 }}</del>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>

<div class="enzo">Este es el modal que aparece</div>