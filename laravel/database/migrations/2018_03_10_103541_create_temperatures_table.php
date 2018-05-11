<?php
/**
 * Generate table "temperatures"
 *
 * @author Jakub Handzus
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemperaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temperatures', function (Blueprint $table) {
            $table->engine = 'MYISAM';
            $table->smallInteger('sensor_id')->unsigned();
            $table->timestamp('time');
            $table->decimal('temperature', 3, 1);
            $table->primary(array('sensor_id', 'time'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temperatures');
    }
}
