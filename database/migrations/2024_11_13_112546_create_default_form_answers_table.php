<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('default_form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_id')
            ->constrained('dynamic_forms')
            ->onDelete('cascade');  
            $table->foreignId('dynamic_form_field_id')
            ->constrained('dynamic_form_fields')
            ->onDelete('cascade'); 
            $table->foreignId('default_user_id')
            ->constrained('default_users')
            ->onDelete('cascade');  
            $table->string('answer')->nullable(); 
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
        Schema::dropIfExists('default_form_answers');
    }
};
