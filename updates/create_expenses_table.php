<?php namespace AWME\Stocket\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateExpensesTable extends Migration
{

    public function up()
    {
        Schema::create('awme_stocket_expenses', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->decimal('amount', 10, 2)->nullable();

			$table->date('expiration')->nullable();

            $table->longText('description');

            $table->enum('status', ['pending', 
                                    'paid'])->default('pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('awme_stocket_expenses');
    }
}
