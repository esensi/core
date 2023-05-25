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
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('uuid')->unique()->after('id');
            $table->longText('exception')->after('payload');

            $table->text('connection')->change();
            $table->text('queue')->change();
            $table->timestamp('failed_at')->useCurrent()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('connection', 128)->change();
            $table->string('queue', 128)->change();
            $table->timestamp('failed_at')->default('0000-00-00')->change();

            $table->dropColumn([
                'uuid',
                'exception',
            ]);
        });
    }
};
