<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');

            $table->string('d_codigo', 8)
                ->index();

            $table->string('d_asenta')
                ->nullable()
                ->default(null);

            $table->string('d_tipo_asenta', 100)
                ->nullable()
                ->default(null);

            $table->string('D_mnpio')
                ->nullable()
                ->default(null);

            $table->string('d_estado', 100)
                ->nullable()
                ->default(null);

            $table->string('d_ciudad')
                ->nullable()
                ->default(null);

            $table->string('d_CP', 8)
                ->nullable()
                ->default(null);

            $table->integer('c_estado')
                ->nullable()
                ->index()
                ->unsigned();

            $table->integer('c_oficina')
                ->nullable()
                ->index()
                ->unsigned();

            $table->integer('c_CP')
                ->nullable()
                ->index()
                ->unsigned();

            $table->integer('c_tipo_asenta')
                ->nullable()
                ->index()
                ->unsigned();

            $table->integer('c_mnpio')
                ->nullable()
                ->index()
                ->unsigned();

            $table->integer('id_asenta_cpcons')
                ->nullable()
                ->index()
                ->unsigned();

            $table->string('d_zona', 100)
                ->nullable()
                ->default(null);

            $table->integer('c_cve_ciudad')
                ->nullable()
                ->index()
                ->unsigned();
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
