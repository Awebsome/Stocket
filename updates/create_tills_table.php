<?php namespace AWME\Stocket\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTillsTable extends Migration
{

    public function up()
    {
        Schema::create('awme_stocket_tills', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('action');
            
            $table->string('seller');

            $table->decimal('cash', 10, 2)->nullable();
            $table->decimal('credit_card', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('till', 10, 2)->nullable();

            $table->longText('description');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('awme_stocket_tills');
    }
}
