<div>
    <div class="grey-bg container-fluid">
        <section id="stats-subtitle">
        @if(session("mensaje"))
            <div class="bg-green round-md alert alert-success">
                {{ session('mensaje') }}
            </div>
        @endif
            <!-- PRINCIPAL -->
            <!-- ========= -->
            <div class="row">
                <div class="col-xl-12 col-md-12 mt-3">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="media align-items-stretch">
                                    <div class="media-body">
                                        <div class="flex d-flex justify-content-beetwen">
                                            <div class="flex d-flex col-9">
                                                <h4>Listado de Roles</h4>
                                                <button type="button" class="ml-3 mb-1 btn btn-info" wire:click="showNew()" data-toggle="modal" data-target="#ModalEdit">
                                                    Nuevo
                                                </button>
                                            </div>
                                            <div class="col-3">
                                                <input wire:model="buscar" type="text" class="form-control rounded-md" placeholder="Buscar">
                                            </div>
                                        </div>
                                        <table class="table table-hover text-nowrap table-rounded">
                                            <tr>
                                                <td>Rol</td>
                                                <td>Opciones</td>
                                            </tr>
                                            @if($roles)
                                                @foreach ($roles as $rol)
                                                <tr>
                                                    <td>{{ $rol->name }}</td>
                                                    <td>
                                                        <button type="button" wire:click="showEdit({{$rol->id}})" class="btn btn-warning" data-toggle="modal" data-target="#ModalEdit">
                                                            Editar
                                                        </button>
                                                        <button type="button" wire:click="showDelete({{$rol->id}})" class="btn btn-danger" data-toggle="modal" data-target="#ModalDelete">
                                                            Eliminar
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Alta/Modificación Rol -->
            <!-- ================================== -->
            <div wire:ignore.self class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog " role="document">
                    <div class="modal-content" style="width: inherit">
                        <div class="modal-header">
                            <h5 class="modal-title">Alta/Modificación Roles</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="px-3 py-3">
                            <div>
                                <label for="">Nombre del Rol</label>
                                {{-- <input type="text" class="form-control" value="{{ old('name') }}" wire:model="name"> --}}
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="">Módulos del sistema</label>
                                <div>
                                    @foreach ($modulos as $modulo)
                                        @if($modulo_seleccionado == $modulo->id)
                                            <button type="button" class="btn btn-success" wire:click="SeleccionarModulo({{ $modulo->id }},'{{ $modulo->name }}')">
                                                <i class="fa-solid fa-pen-to-square"></i>{{ $modulo->name }}
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-outline-success mb-1" wire:click="SeleccionarModulo({{ $modulo->id }},'{{ $modulo->name }}')">
                                                <i class="fa-solid fa-pen-to-square"></i>{{ $modulo->name }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label for="">Permisos Disponibles</label>
                                <div>
                                    @if(!is_null($permisos))
                                        @foreach ($permisos as $permiso)
                                            @if($modulo_seleccionado == $modulo->id)
                                                <button type="button" class="btn btn-success" wire:click="SeleccionarModulo(0,'a')">
                                                    <i class="fa-solid fa-pen-to-square"></i>{{ $permiso->name }}
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-success mb-1">
                                                    {{ $permiso->name }}
                                                    <span style="color: white; margin-left: 10px; background-color: rgb(64, 185, 9); border-radius: 3px; width: 20px; display: inline-block;vertical-align: middle;" aria-hidden="true"  title="Agregar Permiso" tooltips="prueba" wire:click="AgregarPermiso({{$permiso->id}})">&plus;</span>
                                                </button>
                                            @endif
                                        @endforeach
                                    @else
                                            Ningún permiso disponible
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label for="">Permisos Habilitados</label>
                                <div>
                                    @if(!is_null($permisoshabilitados))
                                        @foreach ($permisoshabilitados as $permisohabilitado)
                                            <button type="button" class="btn btn-success mb-1 ml-1">
                                                {{ $permisohabilitado->name }}
                                                <span style="margin-left: 10px; background-color: rgb(181, 26, 26); border-radius: 3px; width: 20px; display: inline-block;vertical-align: middle;" aria-hidden="true"  title="Eliminar Permiso" tooltips="prueba" wire:click="EliminarPermiso({{$permisohabilitado->permission_id}},{{ $permisohabilitado->role_id}})">&times;</span>
                                            </button>
                                        @endforeach
                                    @else
                                        Ningún permiso habilitado
                                    @endif
                                </div>
                            </div>

                            <div class="pt-3">
                                {{-- <button type="button" class="btn btn-success"  data-dismiss="modal" wire:click="store()">
                                    <i class="fa-solid fa-pen-to-square"></i>Guardar
                                </button> --}}
                                <button type="button" class="btn btn-info" data-dismiss="modal" aria-label="Close">
                                    <i class="fa-solid fa-pen-to-square"></i>Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Eliminar Rol -->
            <!-- ====================== -->
            <div wire:ignore.self class="modal fade" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog " role="document">
                    <div class="modal-content" style="width: inherit">
                        <div class="modal-header">
                            <h5 class="modal-title">Eliminar Rol</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="px-3 py-3">
                            <div>
                                Está seguro de que quiere eliminar el rol: <b>{{ $name }}</b>?
                            </div>
                            <div class="pt-3">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="destroy({{ $rol_id }})">
                                    <i class="fa-solid fa-pen-to-square"></i>Eliminar
                                </button>
                                <button type="button" class="btn btn-info" data-dismiss="modal" aria-label="Close">
                                    <i class="fa-solid fa-pen-to-square"></i>Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>