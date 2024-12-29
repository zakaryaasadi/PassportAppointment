<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name')->default(''); // Name as string
            $table->string('phone')->default(''); // Phone as string
            $table->integer('status')->default(0);
            $table->string('type')->default(''); // Type as string
            $table->dateTime('creation_date')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrent(); // UTC creation date
            $table->string('start_appointment_date'); // Start appointment datetime
            $table->string('end_appointment_date'); // End appointment datetime
            $table->timestamps(); // Laravel timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
