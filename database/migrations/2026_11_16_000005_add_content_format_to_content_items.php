<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_items', function (Blueprint $table) {
            if (! Schema::hasColumn('content_items', 'content_format')) {
                $table->string('content_format', 20)->default('text')->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('content_items', function (Blueprint $table) {
            if (Schema::hasColumn('content_items', 'content_format')) {
                $table->dropColumn('content_format');
            }
        });
    }
};
