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
        Schema::table('record_statuses', function (Blueprint $table) {
            $table->enum('badge', [
                'text-bg-primary',
                'text-bg-secondary',
                'text-bg-success',
                'text-bg-danger',
                'text-bg-warning',
                'text-bg-info',
                'text-bg-light',
                'text-bg-dark',
            ])->default('text-bg-secondary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('record_statuses', function (Blueprint $table) {
            $table->dropColumn('badge');
        });
    }
};
