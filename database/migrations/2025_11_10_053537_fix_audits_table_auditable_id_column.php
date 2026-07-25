<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Convert auditable_id from BIGINT to UUID. morphs() created the
     * auditable_type/auditable_id columns plus a compound index named
     * audits_auditable_type_auditable_id_index. dropMorphs() removes both
     * columns AND the index correctly (SQLite errors if the index survives a
     * dropColumn). uuidMorphs() recreates them with auditable_id as UUID.
     */
    public function up(): void
    {
        $connection = config(
            "audit.drivers.database.connection",
            config("database.default"),
        );
        $table = config("audit.drivers.database.table", "audits");

        Schema::connection($connection)->table($table, function (
            Blueprint $table,
        ) {
            $table->dropMorphs("auditable");
            $table->uuidMorphs("auditable");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config(
            "audit.drivers.database.connection",
            config("database.default"),
        );
        $table = config("audit.drivers.database.table", "audits");

        Schema::connection($connection)->table($table, function (
            Blueprint $table,
        ) {
            $table->dropMorphs("auditable");
            $table->morphs("auditable");
        });
    }
};
