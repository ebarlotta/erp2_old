<?php

use App\Models\Area;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->string('comprobante')->nullable();
            $table->string('detalle')->nullable();

            $table->double('BrutoComp')->default(0);
            $table->string('ParticIva')->default("No");
            $table->double('MontoIva')->nullable()->default(0);
            $table->double('ExentoComp')->nullable()->default(0);
            $table->double('ImpInternoComp')->nullable()->default(0);
            $table->double('PercepcionIvaComp')->nullable()->default(0);
            $table->double('RetencionIB')->nullable()->default(0);
            $table->double('RetencionGan')->nullable()->default(0);
            $table->double('NetoComp')->nullable()->default(0);
            $table->double('MontoPagadoComp')->nullable()->default(0);
            $table->double('CantidadLitroComp')->nullable()->default(0);
            $table->boolean('Cerrado')->default(0);
            $table->double('Anio')->default(0);
            $table->integer('PasadoEnMes')->default(0);

            $table->unsignedBigInteger('iva_id');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('cuenta_id');
            $table->unsignedBigInteger('user_id');
            //$table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('empresa_id');

            
            $table->foreign('iva_id')->references('id')->on('ivas');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('cuenta_id')->references('id')->on('cuentas');
            $table->foreign('user_id')->references('id')->on('users');
            //$table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('proveedor_id')->references('id')->on('proveedors');
            $table->foreign('empresa_id')->references('id')->on('empresas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobantes');
    }
}
