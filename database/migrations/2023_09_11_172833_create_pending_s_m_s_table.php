<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingSMSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_s_m_s', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('message');
            $table->date('scheduled_at')->nullable();
            $table->enum('status', ['pending', 'deliver','error'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_s_m_s');
    }
}
