<?php namespace AWME\Stocket\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateSalesTable extends Migration
{

    public function up()
    {
        Schema::create('awme_stocket_sales', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('invoice')->nullable();

            $table->string('fullname');
            $table->string('dni')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();

            $table->string('seller')->nullable();
            $table->longText('description')->nullable();
            
            $table->decimal('subtotal', 10, 2)->default(0)->nullable();
            $table->string('taxes')->nullable();    # type: [amount, type] 
            $table->decimal('total', 10, 2)->default(0)->nullable();

            $table->string('payment')->default('cash');

            $table->enum('status', ['open', 
                                    'pending',
                                    'closed',
                                    'canceled'])->default('open');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('awme_stocket_sales');
    }
}
