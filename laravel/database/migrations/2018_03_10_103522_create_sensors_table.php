<?php
/**
 * Generate table "sensors"
 *
 * @author Jakub Handzus
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->engine = 'MYISAM';
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->smallInteger('user_id')->unsigned();
            $table->string('name', 20);
            $table->string('api_key', 34)->unique();
            $table->string('device_id', 34)->nullable();
            $table->boolean('confirmed')->default(0);
            $table->decimal('min', 3, 1)->nullable();
            $table->decimal('max', 3, 1)->nullable();
            $table->timestamp('temperature_time')->nullable();
            $table->decimal('last_temperature', 3, 1)->nullable();
            $table->timestamp('cleanup_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensors');
    }
}
