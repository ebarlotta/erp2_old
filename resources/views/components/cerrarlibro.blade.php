@props(['id'])
<div class="flex justify-center">
    <!-- Desde 640 en adelante -->
    <span class="mt-3 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
        <button wire:click="CerrarLibro()" class="hidden sm:flex bg-red-300 hover:bg-red-400 text-black-900 font-bold py-2 px-4 rounded mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Cerrar Libro
        </button>
    </span>
    <!-- Menos 640 en adelante -->
    <button wire:click="CerrarLibro()" class="sm:hidden flex bg-red-300 hover:bg-red-400 text-black-900 font-bold py-2 px-4 rounded mt-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>