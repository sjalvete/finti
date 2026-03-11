<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->renameColumn('parent_id', 'chapter_id');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('chapter_id')
                ->references('id')
                ->on('chapters')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['chapter_id']);
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->renameColumn('chapter_id', 'parent_id');
        });
    }
};
