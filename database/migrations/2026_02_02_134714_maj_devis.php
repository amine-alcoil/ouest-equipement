<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('devis', 'type_exchangeur')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->string('type_exchangeur')->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'cuivre_diametre')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->decimal('cuivre_diametre', 10, 2)->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'pas_ailette')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->decimal('pas_ailette', 10, 2)->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'hauteur_mm')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->decimal('hauteur_mm', 10, 2)->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'largeur_mm')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->decimal('largeur_mm', 10, 2)->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'longueur_mm')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->decimal('longueur_mm', 10, 2)->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'longueur_totale_mm')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->decimal('longueur_totale_mm', 10, 2)->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'collecteur1_diametre')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->decimal('collecteur1_diametre', 10, 2)->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'collecteur2_diametre')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->decimal('collecteur2_diametre', 10, 2)->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'nombre_tubes')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->integer('nombre_tubes')->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'geometrie_x_mm')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->decimal('geometrie_x_mm', 10, 2)->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'geometrie_y_mm')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->decimal('geometrie_y_mm', 10, 2)->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'collecteur1_nb')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->integer('collecteur1_nb')->nullable();
            });
        }
        if (!Schema::hasColumn('devis', 'collecteur2_nb')) {
            Schema::table('devis', function (Blueprint $table) {
                $table->integer('collecteur2_nb')->nullable();
            });
        }
    }

    public function down(): void {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropColumn([
                'type_exchangeur','cuivre_diametre','pas_ailette',
                'hauteur_mm','largeur_mm','longueur_mm','longueur_totale_mm',
                'collecteur1_diametre','collecteur2_diametre',
                'nombre_tubes','geometrie_x_mm','geometrie_y_mm','collecteur1_nb','collecteur2_nb'
            ]);
        });
    }
};