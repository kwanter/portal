<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
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
            // Drop the existing auditable_id column and recreate it as UUID
            $table->dropColumn("auditable_id");
        });

        Schema::connection($connection)->table($table, function (
            Blueprint $table,
        ) {
            // Add auditable_id back as UUID type
            $table->uuid("auditable_id")->after("auditable_type");

            // Recreate the index
            $table->index(["auditable_id", "auditable_type"]);
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
            // Drop the UUID column
            $table->dropColumn("auditable_id");
        });

        Schema::connection($connection)->table($table, function (
            Blueprint $table,
        ) {
            // Restore the original BIGINT column
            $table->unsignedBigInteger("auditable_id")->after("auditable_type");

            // Recreate the index
            $table->index(["auditable_id", "auditable_type"]);
        });
    }
};
