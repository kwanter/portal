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
        Schema::create("applications", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name");
            $table->string("url", 500);
            $table->text("description");
            $table->enum("category", ["kesekretariatan", "kepaniteraan"]);

            // Audit fields
            $table->uuid("created_by");
            $table->uuid("updated_by")->nullable();
            $table->uuid("deleted_by")->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table
                ->foreign("created_by")
                ->references("id")
                ->on("users")
                ->onDelete("restrict");
            $table
                ->foreign("updated_by")
                ->references("id")
                ->on("users")
                ->onDelete("set null");
            $table
                ->foreign("deleted_by")
                ->references("id")
                ->on("users")
                ->onDelete("set null");

            // Indexes
            $table->index("category");
            $table->index("name");
            $table->index("deleted_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("applications");
    }
};
