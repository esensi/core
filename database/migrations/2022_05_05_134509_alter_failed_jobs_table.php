<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterFailedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('failed_jobs', function (Blueprint $table) {

            // drop indexes
            $table->dropIndex('failed_jobs_connection_index');
            $table->dropIndex('failed_jobs_queue_index');
            $table->dropIndex('failed_jobs_failed_at_index');
        
            // add new columns
            $table->string('uuid')->unique()->after('id');
            $table->longText('exception')->after('payload');
        
            // change existing columns
            $table->text('connection')->change();
            $table->text('queue')->change();
            DB::statement("ALTER TABLE failed_jobs MODIFY COLUMN failed_at TIMESTAMP NOT NULL DEFAULT current_timestamp();");
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
            $table->string('connection', 128)->index()->change();
            $table->string('queue', 128)->index()->change();
            DB::statement("ALTER TABLE failed_jobs MODIFY COLUMN failed_at TIMESTAMP NOT NULL DEFAULT '0000-00-00' KEY `failed_jobs_failed_at_index` (`failed_at`);");
            $table->dropColumn('uuid');
            $table->dropColumn('exception');
        });
    }
}
