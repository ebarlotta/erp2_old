@props(['id'])
<div class="flex justify-center">
    <!-- Desde 640 en adelante -->
    <button wire:click="edit({{$id}})" class="hidden lg:flex bg-blue-300 hover:bg-blue-400 text-black-900 font-bold py-2 px-4 mr-2 rounded">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
        Editar
    </button>
    <!-- Menos 640 en adelante -->
    <button wire:click="edit({{$id}})" class="lg:hidden bg-blue-300 hover:bg-blue-400 text-black-900 font-bold py-1 px-1 mt-1 rounded">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
    </button>
</div>