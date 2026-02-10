<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

       
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->string('ref_id')->unique()->nullable(); // For ALC-DEV-XXXXX
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('type')->nullable(); // standard or specific
            $table->string('product')->nullable();
            $table->integer('quantity')->nullable();
            $table->text('message')->nullable();
            $table->text('requirements')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('status')->default('nouveau');
            $table->timestamps();
        });

         Schema::table('devis', function (Blueprint $table) {
    // Add client_id if you want to link to clients table
$table->unsignedBigInteger('client_id')->nullable();
// Foreign key will be added after clients table exists    
    
    // Add date field
    $table->timestamp('date')->nullable()->after('message');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
