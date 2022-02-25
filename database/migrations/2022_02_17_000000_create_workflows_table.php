<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->uuid('uuid')->default(new Expression('(uuid())'))->primary();
            $table->string('version', 80)->default('1.0');
            $table->string('spec_version', 80)->default('1.0');
            $table->string('name')->index();
            $table->text('friendly_name')->nullable();
            $table->string('hash')->index();
            $table->enum('publish_status', ['draft', 'published'])->default('draft');
            $table->json('dsl');
            $table->boolean('is_callable')->default(false);
            $table->string('created_by', 80);
            $table->dateTime('created_at');
        });
        Schema::create('workflow_audit_logs', function (Blueprint $table) {
            $table->uuid('uuid')->default(new Expression('(uuid())'))->primary();
            $table->enum('action', ['create', 'update', 'delete']);
            $table->string('column');
            $table->text('old_value');
            $table->text('new_value');
            $table->string('created_by', 80);
            $table->dateTime('created_at');
            $table->foreignUuid('workflow_uuid')->references('uuid')->on('workflows');
        });
        Schema::create('workflow_runs', function (Blueprint $table) {
            $table->uuid('uuid')->default(new Expression('(uuid())'))->primary();
            $table->json('input')->nullable();
            $table->json('metadata')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->foreignUuid('workflow_uuid')->references('uuid')->on('workflows');
        });
        Schema::create('object_references', function (Blueprint $table) {
            $table->uuid('uuid')->default(new Expression('(uuid())'))->primary();
            $table->string('object_type', 80);
            $table->string('object_id', 80);
        });
        Schema::create('workflow_run_object_reference', function (Blueprint $table) {
            $table->foreignUuid('workflow_uuid')->references('uuid')->on('workflows');
            $table->foreignUuid('object_reference_uuid')->references('uuid')->on('object_references');
        });
        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('uuid')->default(new Expression('(uuid())'))->primary();
            $table->string('version', 80)->default('1.0');
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->json('events_dsl');
            $table->json('functions_dsl');
            $table->json('dsl');
            $table->string('created_by', 80);
            $table->dateTime('created_at');
            $table->string('updated_by', 80);
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
        Schema::dropIfExists('workflow_run_object_reference');
        Schema::dropIfExists('object_references');
        Schema::dropIfExists('workflow_runs');
        Schema::dropIfExists('workflow_audit_logs');
        Schema::dropIfExists('workflows');
    }
}
