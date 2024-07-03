<?php

namespace App\Http\Livewire\Modulo;

use App\Models\EmpresaModulo;
use App\Models\Modulo;
use Livewire\Component;
use App\Models\Roles;

class ModuloComponent extends Component
{
    public $empresa_id;
    public $modulos;

    public function render()
    {
        if(session('empresa_id')) {
        $empresa_modulos = EmpresaModulo::where('empresa_id',session('empresa_id'))->get('modulo_id');
        //dd(session('empresa_id'));
        //dd($empresa_modulos);

        $rol = new Roles;
        $rol->Permisos();

        $this->modulos=Modulo::find($empresa_modulos);
        return view('livewire.modulo.modulo-component',$this->modulos)->extends('layouts.adminlte')
        ->section('content');
        } else {
            return view('livewire.empresa.empresa-component');
        }
    }
}

// INSERT INTO `empresa_usuarios` (`id`, `empresa_id`, `user_id`, `created_at`, `updated_at`) VALUES
// (4,  4, 11, 2,'2022-07-27 07:36:07', '2022-07-27 07:36:07'),
// (5,  1, 11, 2,'2022-07-27 07:36:07', '2022-07-27 07:36:07'),
// (7,  1, 11, 2, '2022-07-27 07:36:07', '2022-07-27 07:36:07'),
// (9,  4, 11, 2,'2022-07-27 07:36:07', '2022-07-27 07:36:07'),
// (10, 1, 11, 2, '2022-07-27 07:36:07', '2022-07-27 07:36:07'),
// (11, 1, 11, 2, NULL, NULL),
// (12, 1, 11, 2, '2023-02-03 17:39:29', '2023-02-03 17:39:29'),
// (15, 2, 11, 2, '2023-02-21 06:30:35', '2023-02-21 06:30:35'),
// (20, 4, 11, 2, '2024-05-05 05:50:46', '2024-05-05 05:50:46'),
// (21, 5, 11, 2,'2024-05-05 05:50:57', '2024-05-05 05:50:57'),
// (22, 5, 11, 2, '2024-05-05 05:51:08', '2024-05-05 05:51:08'),
// (23, 6, 11, 2,'2024-05-05 05:51:20', '2024-05-05 05:51:20'),
// (24, 6, 11, 2, '2024-05-05 05:51:29', '2024-05-05 05:51:29');
