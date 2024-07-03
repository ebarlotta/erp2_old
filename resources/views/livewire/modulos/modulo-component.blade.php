<div>
    <div class="grey-bg container-fluid">
        <section id="stats-subtitle">
        @if(session("mensaje"))
            <div class="bg-green round-md alert alert-success">
                {{ session('mensaje') }}
            </div>
        @endif
            <div class="row">
                <div class="col-xl-12 col-md-12 mt-3">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="media align-items-stretch">
                                    <div class="media-body">
                                        <div class="flex d-flex justify-content-beetwen">
                                            <div class="flex d-flex col-9">
                                                <h4>Listado de Módulos</h4>
                                                <button type="button" class="ml-3 mb-1 btn btn-info" wire:click="showNew()" data-toggle="modal" data-target="#ModalEdit">
                                                    Nuevo
                                                </button>
                                                <div class="w-1/2 justify-end">{{ $datos->links() }}</div>
                                            </div>
                                            <div class="col-3">
                                                <input wire:model="buscar" type="text" class="form-control rounded-md" placeholder="Buscar">
                                            </div>
                                        </div>
                                        <table class="table table-hover text-nowrap table-rounded">
                                            <tr>
                                                <td>Módulo</td>
                                                <td>Opciones</td>
                                            </tr>
                                            @if($datos)
                                                @foreach ($datos as $modulo)
                                                <tr>
                                                    <td>{{ $modulo->name }}</td>
                                                    <td>
                                                        <button type="button" wire:click="showEdit({{$modulo->id}})" class="btn btn-warning" data-toggle="modal" data-target="#ModalEdit">
                                                            Editar
                                                        </button>
                                                        <button type="button" wire:click="showDelete({{$modulo->id}})" class="btn btn-danger" data-toggle="modal" data-target="#ModalDelete">
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

            <!-- Modal Alta/Modificación Módulo -->
            <!-- ================================== -->
            <div wire:ignore.self class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog " role="document" style="max-width: 80%;">
                    <div class="modal-content" style="width: inherit">
                        <div class="modal-header">
                            <h5 class="modal-title">Alta/Modificación Módulos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="px-3 py-3">
                            <div>
                                <label for="">Nombre del Módulo</label>
                                <input type="text" class="form-control" value="{{ old('name') }}" wire:model="name">
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <label for="">
                                    Permisos <button type="button" class="btn btn-success mb-1 ml-2">Agregar
                                                <span class="col-1 fs-3 ml-1" aria-hidden="true" style="lightcoral;border-radius: 5px; text-align: center;vertical-align: middle;background-color: lightgreen;" wire:click="showNewPermiso()" data-toggle="modal" data-target="#ModalAddPermission">&plus;</span>
                                                </button>
                                </label><br>
                                @if($permisos)
                                    @foreach ($permisos as $permiso)
                                    {{-- <div class="d-flex"> --}}
                                        <button type="button" class="btn btn-outline-success mb-1">
                                            {{ $permiso->name }}
                                        {{-- <input type="text" class="form-control col-11 pt-2" value="{{ $permiso->name }}"> --}}
                                        {{-- <span style="color: white; margin-left: 10px; background-color: rgb(64, 185, 9); border-radius: 3px; width: 20px; display: inline-block;vertical-align: middle;" aria-hidden="true"  title="Agregar Permiso" tooltips="prueba" wire:click="AgregarPermiso({{$permiso->id}})">&plus;</span> --}}
                                        <span style="color:white; margin-left: 10px; background-color: rgb(189, 129, 129); border-radius: 3px; width: 20px; display: inline-block;vertical-align: middle;" aria-hidden="true" title="Eliminar Permiso" wire:click="getPermisoaEliminar({{$permiso->id}}, '{{ $permiso->name }}')" data-toggle="modal" data-target="#ModalDeletePermiso" >&times;</span>
                                        {{-- <span class="col-1 fs-3" aria-hidden="true" style="lightcoral;border-radius: 10px; text-align: center;vertical-align: middle;background-color: lightcoral;">&times;</span> --}}
                                        </button>
                                    {{-- </div> --}}
                                    @endforeach
                                @endif
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
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

            <!-- Modal Agregar nuevo Permiso -->
            <!-- =========================== -->
            <div wire:ignore.self class="modal fade" id="ModalAddPermission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog " role="document">
                    <div class="modal-content" style="width: inherit">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar Permiso</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="px-3 py-3">
                            <div>
                                <label for="">Nombre del Permiso</label>
                                <input type="text" class="form-control" value="{{ old('name') }}" wire:model="nombre_permiso">
                                @error('nombre_permiso')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="pt-3">
                                <button type="button" class="btn btn-success"  data-dismiss="modal" wire:click="storePermiso()">
                                    <i class="fa-solid fa-pen-to-square"></i>Guardar
                                </button>
                                <button type="button" class="btn btn-info" data-dismiss="modal" aria-label="Close">
                                    <i class="fa-solid fa-pen-to-square"></i>Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal Eliminar Permiso -->
            <!-- ====================== -->
            <div wire:ignore.self class="modal fade" id="ModalDeletePermiso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog " role="document">
                    <div class="modal-content" style="width: inherit">
                        <div class="modal-header">
                            <h5 class="modal-title">Eliminar Permiso del Módulo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="px-3 py-3">
                            <div>
                                Está seguro de que quiere eliminar el permiso del módulo: <b>{{ $nombre_permiso }}</b>?
                            </div>
                            <div class="pt-3">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="destroyPermiso({{ $idpermisoaeliminar }})">
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



            <!-- Modal Eliminar Módulo -->
            <!-- ====================== -->
            <div wire:ignore.self class="modal fade" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog " role="document" style="max-width: 80%;">
                    <div class="modal-content" style="width: inherit">
                        <div class="modal-header">
                            <h5 class="modal-title">Eliminar Módulo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="px-3 py-3">
                            <div>
                                Está seguro de que quiere eliminar el rol: <b>{{ $name }}</b>?
                            </div>
                            <div class="pt-3">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="destroy({{ $modulo_id }})">
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