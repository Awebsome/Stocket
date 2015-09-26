<?php namespace AWME\Stocket\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateItemsSalesTable extends Migration
{

    public function up()
    {
        Schema::create('awme_stocket_items_sales', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            
            $table->integer('sale_id');
            $table->integer('product_id');

            $table->smallInteger('qty')->default(1);
            $table->decimal('sale_price', 10, 2)->default(0)->nullable();
            $table->decimal('subtotal', 10, 2)->default(0)->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('awme_stocket_items_sales');
    }
}