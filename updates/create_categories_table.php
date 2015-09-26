<?php namespace AWME\Stocket\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCategoriesTable extends Migration
{

    public function up()
    {
        Schema::create('awme_stocket_categories', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->index();
            $table->string('slug')->index()->unique();
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->integer('nest_left')->default(0);
            $table->integer('nest_right')->default(0);
            $table->integer('nest_depth')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('awme_stocket_categories');
    }
}
