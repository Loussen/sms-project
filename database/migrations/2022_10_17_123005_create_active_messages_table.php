<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiveMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('instance_id');
            $table->string('to');
            $table->text('body');
            $table->integer('priority')->default(10);
            $table->bigInteger('referance_id')->comment('Shirketden gelen ID bura yazilacaq');
            $table->timestamp('send_at')->nullable();
            $table->enum('status', [1,2,3,4])->default('2')->comment('1 => sent, 2 => queue, 3 => unsent, 4 => invalid');
            $table->enum('ack_status', [1,2,3,4,5])->default('1')->comment('1 => pending, 2 => server,  3 => device, 4 => read, 5 => played');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('active_messages');
    }
}
