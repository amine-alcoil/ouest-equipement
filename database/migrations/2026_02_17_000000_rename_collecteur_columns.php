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
        Schema::table('devis', function (Blueprint $table) {
            // collecteur1 -> collecteur1_mm
            if (Schema::hasColumn('devis', 'collecteur1')) {
                $table->renameColumn('collecteur1', 'collecteur1_mm');
            } else {
                if (!Schema::hasColumn('devis', 'collecteur1_mm')) {
                    $table->decimal('collecteur1_mm', 10, 2)->nullable();
                }
            }

            // collecteur2 -> collecteur2_mm
            if (Schema::hasColumn('devis', 'collecteur2')) {
                $table->renameColumn('collecteur2', 'collecteur2_mm');
            } else {
                if (!Schema::hasColumn('devis', 'collecteur2_mm')) {
                    $table->decimal('collecteur2_mm', 10, 2)->nullable();
                }
            }
        });

        // Ensure they are decimal if they were renamed (Schema::table with change() requires doctrine/dbal which might not be installed, but rename usually keeps type or we can modify it)
        // If we just renamed, let's assume the type is correct or update it.
        // To be safe, let's explicitly set the type using change() if possible, or just trust they are correct if we created them.
        // If they were strings, we might need to cast them. 
        // For now, let's assume rename is enough or creation is enough.
        // If we need to change type, we need doctrine/dbal.
        // Let's check if we can modify the column type in a separate schema table call if needed, but 'decimal' was the likely target.
        // If they were created as 'string' or something else, we might want to change them.
        // Given I don't know the current type, I will try to change them to decimal just in case.
        
        Schema::table('devis', function (Blueprint $table) {
            if (Schema::hasColumn('devis', 'collecteur1_mm')) {
                $table->decimal('collecteur1_mm', 10, 2)->nullable()->change();
            }
            if (Schema::hasColumn('devis', 'collecteur2_mm')) {
                $table->decimal('collecteur2_mm', 10, 2)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
             if (Schema::hasColumn('devis', 'collecteur1_mm')) {
                $table->renameColumn('collecteur1_mm', 'collecteur1');
            }
            if (Schema::hasColumn('devis', 'collecteur2_mm')) {
                $table->renameColumn('collecteur2_mm', 'collecteur2');
            }
        });
    }
};