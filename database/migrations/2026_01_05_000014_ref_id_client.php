<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('ref_id')->nullable()->unique();
        });

        DB::table('clients')->orderBy('id')->chunk(200, function ($rows) {
            foreach ($rows as $row) {
                if (empty($row->ref_id)) {
                    $ref = 'ALC-' . str_pad((string)$row->id, 4, '0', STR_PAD_LEFT);
                    DB::table('clients')->where('id', $row->id)->update(['ref_id' => $ref]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique(['ref_id']);
            $table->dropColumn('ref_id');
        });
    }
};