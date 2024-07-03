{{-- <div id="DivEmpleados"> --}}
                                                        {{-- @if (!is_null($EmpleadosActivos)) --}}
                                                            {{-- <select wire:model="empleadoseleccionado" wire:change="cargaIdEmpleado({{$empleadoseleccionado}});> --}}
                                                                {{-- wire:change="dibujarRecibo();" 
                                                                wire:model="empleadoseleccionado1"
                                                                wire:model="empleadoseleccionado"
                                                                name="empleadoseleccionado" id="empleadoseleccionado"
                                                                --}}
                                                                {{-- <option value="0">-</option> --}}
                                                            
                                                                {{-- @foreach ($EmpleadosActivos as $empleado) --}}
                                                                    {{-- <option value="{{ !empty($empleado->id) ? $empleado->id:$empleado['id'] }}"> --}}
                                                                        {{-- {{ !empty($empleado->name) ? $empleado->name.'*' : $empleado['name'] }} --}}
                                                                    {{-- </option> --}}
                                                                    {{-- <option value="{{ $empleado->id }}">
                                                                        {{ $empleado->name }}
                                                                    </option> --}}
                                                                    {{-- <option value="{{ $empleado['id'] }}">
                                                                        {{ $empleado['name'] }}
                                                                    </option> --}}
                                                                {{-- @endforeach --}}
                                                            {{-- </select> --}}
                                                        {{-- @endif --}}
                                                        {{-- </div> --}}