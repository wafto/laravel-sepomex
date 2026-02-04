<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateSepomexTable.
 */
class CreateSepomexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('sepomex.table_name'), function (Blueprint $table) {
            $table->id();

            $table->string('d_codigo', 8)
                ->index();

            $table->string('d_asenta')
                ->nullable();

            $table->string('d_tipo_asenta', 100)
                ->nullable();

            $table->string('D_mnpio')
                ->nullable();

            $table->string('d_estado', 100)
                ->nullable();

            $table->string('d_ciudad')
                ->nullable();

            $table->string('d_CP', 8)
                ->nullable();

            $table->unsignedInteger('c_estado')
                ->nullable()
                ->index();

            $table->unsignedInteger('c_oficina')
                ->nullable()
                ->index();

            $table->unsignedInteger('c_CP')
                ->nullable()
                ->index();

            $table->unsignedInteger('c_tipo_asenta')
                ->nullable()
                ->index();

            $table->unsignedInteger('c_mnpio')
                ->nullable()
                ->index();

            $table->unsignedInteger('id_asenta_cpcons')
                ->nullable()
                ->index();

            $table->string('d_zona', 100)
                ->nullable();

            $table->unsignedInteger('c_cve_ciudad')
                ->nullable()
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('sepomex.table_name'));
    }
}
