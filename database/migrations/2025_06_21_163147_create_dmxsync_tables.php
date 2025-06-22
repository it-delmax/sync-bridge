<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //⛔️ Commented out the creation of tables to avoid conflicts with existing database structure.



        // // 1. action_kind
        // Schema::create('action_kind', function (Blueprint $table) {
        //     $table->integer('action_kind_id')->primary();
        //     $table->string('name', 64);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // // 2. action_level
        // Schema::create('action_level', function (Blueprint $table) {
        //     $table->string('action_level_id', 3)->primary();
        //     $table->string('name', 64);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // // 3. action_position
        // Schema::create('action_position', function (Blueprint $table) {
        //     $table->string('action_position_id', 3)->primary();
        //     $table->string('name', 64);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // // 4. action_target
        // Schema::create('action_target', function (Blueprint $table) {
        //     $table->string('action_target_id', 3)->primary();
        //     $table->string('name', 64);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // // 5. actions
        // Schema::create('actions', function (Blueprint $table) {
        //     $table->id('actions_id');
        //     $table->integer('action_kind_id');
        //     $table->string('name', 64);
        //     $table->text('query');
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->foreign('action_kind_id')
        //         ->references('action_kind_id')
        //         ->on('action_kind')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->index('action_kind_id');
        // });

        // // 6. data_type
        // Schema::create('data_type', function (Blueprint $table) {
        //     $table->smallInteger('data_type_id')->primary();
        //     $table->string('name', 16)->nullable();
        // });

        // // 7. execution_status
        // Schema::create('execution_status', function (Blueprint $table) {
        //     $table->smallInteger('execution_status_id')->primary();
        //     $table->string('name', 64)->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // // 8. http_method
        // Schema::create('http_method', function (Blueprint $table) {
        //     $table->string('http_method_id', 8)->primary();
        //     $table->string('name', 16)->nullable();
        // });

        // // 9. log_level
        // Schema::create('log_level', function (Blueprint $table) {
        //     $table->smallInteger('log_level_id')->primary();
        //     $table->string('name', 64)->nullable();
        // });

        // // 10. resource_kind
        // Schema::create('resource_kind', function (Blueprint $table) {
        //     $table->smallInteger('resource_kind_id')->primary();
        //     $table->string('name', 64);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // // 11. resource_type
        // Schema::create('resource_type', function (Blueprint $table) {
        //     $table->smallInteger('resource_type_id')->primary();
        //     $table->string('name', 32);
        //     $table->smallInteger('resource_kind_id');
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->foreign('resource_kind_id')
        //         ->references('resource_kind_id')
        //         ->on('resource_kind')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->index('resource_kind_id');
        // });

        // // 12. resource
        // Schema::create('resource', function (Blueprint $table) {
        //     $table->id('resource_id');
        //     $table->smallInteger('resource_type_id');
        //     $table->string('name', 64);
        //     $table->text('db_connection_params')->nullable();
        //     $table->text('log_connection_params')->nullable();
        //     $table->text('web_connection_params')->nullable();
        //     $table->text('app_connection_params')->nullable();
        //     $table->text('log_view_query')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->foreign('resource_type_id')
        //         ->references('resource_type_id')
        //         ->on('resource_type')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->index('resource_type_id');
        // });

        // // 13. profile
        // Schema::create('profile', function (Blueprint $table) {
        //     $table->id('profile_id');
        //     $table->string('name', 32)->unique();
        //     $table->string('info', 128)->nullable();
        //     $table->string('profile_group', 64)->nullable();
        //     $table->unsignedBigInteger('src_resource_id');
        //     $table->unsignedBigInteger('dst_resource_id');
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->foreign('src_resource_id')
        //         ->references('resource_id')
        //         ->on('resource')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('dst_resource_id')
        //         ->references('resource_id')
        //         ->on('resource')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->index('src_resource_id');
        //     $table->index('dst_resource_id');
        // });

        // // 14. profile_const
        // Schema::create('profile_const', function (Blueprint $table) {
        //     $table->id('profile_const_id');
        //     $table->unsignedBigInteger('profile_id');
        //     $table->smallInteger('data_type_id');
        //     $table->string('const_name', 32);
        //     $table->string('const_value', 100)->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->index('profile_id');
        //     $table->index('data_type_id');
        // });

        // // 15. profile_exec_info
        // Schema::create('profile_exec_info', function (Blueprint $table) {
        //     $table->string('profile_name', 32);
        //     $table->string('dmxsync_database', 50);
        //     $table->string('computer_name', 50);
        //     $table->string('ip_address', 15);
        //     $table->string('service_name', 100);
        //     $table->string('working_dir', 255);
        //     $table->string('interval', 15);
        //     $table->string('src_resource_name', 64);
        //     $table->string('dst_resource_name', 64);
        //     $table->timestamps();

        //     $table->primary(['profile_name', 'dmxsync_database']);
        // });

        // // 16. tracking_changes_method
        // Schema::create('tracking_changes_method', function (Blueprint $table) {
        //     $table->smallInteger('tracking_changes_method_id')->primary();
        //     $table->string('name', 64)->nullable();
        // });

        // // 17. trans_level
        // Schema::create('trans_level', function (Blueprint $table) {
        //     $table->smallInteger('trans_level_id')->primary();
        //     $table->string('name', 64)->nullable();
        // });

        // // 18. update_kind
        // Schema::create('update_kind', function (Blueprint $table) {
        //     $table->smallInteger('update_kind_id')->primary();
        //     $table->string('name', 64);
        // });

        // // 19. task
        // Schema::create('task', function (Blueprint $table) {
        //     $table->id('task_id');
        //     $table->unsignedBigInteger('parent_id')->nullable();
        //     $table->smallInteger('child_count')->nullable();
        //     $table->unsignedBigInteger('profile_id');
        //     $table->smallInteger('is_active')->default(0);
        //     $table->string('name', 64)->nullable();
        //     $table->string('src_table', 64)->nullable();
        //     $table->string('dst_table', 64)->nullable();
        //     $table->text('src_query')->nullable();
        //     $table->text('dst_query')->nullable();
        //     $table->string('src_url', 512)->nullable();
        //     $table->string('src_http_method_id', 8)->nullable();
        //     $table->string('dst_url', 512)->nullable();
        //     $table->string('dst_http_method_id', 8)->nullable();
        //     $table->text('src_log_view_query')->nullable();
        //     $table->text('dst_log_view_query')->nullable();
        //     $table->integer('order_index')->nullable();
        //     $table->smallInteger('update_kind_id');
        //     $table->smallInteger('trans_level_id')->nullable();
        //     $table->smallInteger('tracking_changes_method_id')->nullable();
        //     $table->string('track_params', 50)->nullable();
        //     $table->string('es_id_fieldname', 64)->nullable();
        //     $table->text('src_fetch_options')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->foreign('profile_id')
        //         ->references('profile_id')
        //         ->on('profile')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('tracking_changes_method_id')
        //         ->references('tracking_changes_method_id')
        //         ->on('tracking_changes_method')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('trans_level_id')
        //         ->references('trans_level_id')
        //         ->on('trans_level')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('update_kind_id')
        //         ->references('update_kind_id')
        //         ->on('update_kind')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('src_http_method_id')
        //         ->references('http_method_id')
        //         ->on('http_method')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('dst_http_method_id')
        //         ->references('http_method_id')
        //         ->on('http_method')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('parent_id')
        //         ->references('task_id')
        //         ->on('task')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->index('profile_id');
        //     $table->index('parent_id');
        //     $table->index('tracking_changes_method_id');
        //     $table->index('trans_level_id');
        //     $table->index('update_kind_id');
        //     $table->index('src_http_method_id');
        //     $table->index('dst_http_method_id');
        // });

        // // 20. task_action
        // Schema::create('task_action', function (Blueprint $table) {
        //     $table->id('task_action_id');
        //     $table->unsignedBigInteger('task_id');
        //     $table->unsignedBigInteger('actions_id')->nullable();
        //     $table->string('action_target_id', 3)->nullable();
        //     $table->string('action_level_id', 3)->nullable();
        //     $table->string('action_position_id', 3);
        //     $table->smallInteger('order_index')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->foreign('task_id')
        //         ->references('task_id')
        //         ->on('task')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('action_target_id')
        //         ->references('action_target_id')
        //         ->on('action_target')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('action_level_id')
        //         ->references('action_level_id')
        //         ->on('action_level')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('action_position_id')
        //         ->references('action_position_id')
        //         ->on('action_position')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('actions_id')
        //         ->references('actions_id')
        //         ->on('actions')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->index(['task_id', 'action_target_id', 'action_level_id', 'action_position_id'], 'TASK_ACTION_IDX1');
        // });

        // // 21. task_child_count
        // Schema::create('task_child_count', function (Blueprint $table) {
        //     $table->id('task_child_count_id');
        //     $table->unsignedBigInteger('task_id')->unique();
        //     $table->integer('child_count')->nullable();
        //     $table->tinyInteger('image_index')->nullable();

        //     $table->foreign('task_id')
        //         ->references('task_id')
        //         ->on('task')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');
        // });

        // // 22. task_field
        // Schema::create('task_field', function (Blueprint $table) {
        //     $table->id('task_field_id');
        //     $table->unsignedBigInteger('task_id');
        //     $table->string('src_field_name', 64)->nullable();
        //     $table->string('dst_field_name', 64)->nullable();
        //     $table->smallInteger('dst_data_type')->nullable();
        //     $table->smallInteger('dst_data_size')->nullable();
        //     $table->smallInteger('is_where')->default(0);
        //     $table->smallInteger('is_update')->nullable();
        //     $table->smallInteger('order_index')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->foreign('task_id')
        //         ->references('task_id')
        //         ->on('task')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->index('task_id');
        // });

        // // 23. task_log_action
        // Schema::create('task_log_action', function (Blueprint $table) {
        //     $table->id('task_log_action_id');
        //     $table->unsignedBigInteger('task_id');
        //     $table->string('action_target_id', 3);
        //     $table->unsignedBigInteger('actions_id');
        //     $table->smallInteger('execution_status_id')->default(0);
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->foreign('task_id')
        //         ->references('task_id')
        //         ->on('task')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('actions_id')
        //         ->references('actions_id')
        //         ->on('actions')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('execution_status_id')
        //         ->references('execution_status_id')
        //         ->on('execution_status')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->foreign('action_target_id')
        //         ->references('action_target_id')
        //         ->on('action_target')
        //         ->onDelete('cascade')
        //         ->onUpdate('cascade');

        //     $table->index('task_id');
        //     $table->index('actions_id');
        //     $table->index('execution_status_id');
        //     $table->index('action_target_id');
        // });

        // Kreiranje stored procedure za UPDATE_CHILD_COUNT
        // DB::unprepared('
        //     CREATE PROCEDURE UPDATE_CHILD_COUNT(IN p_parent_id INT)
        //     BEGIN
        //         IF p_parent_id IS NOT NULL THEN
        //             UPDATE task
        //             SET child_count = (
        //                 SELECT COUNT(*)
        //                 FROM task AS t2
        //                 WHERE t2.parent_id = p_parent_id
        //             )
        //             WHERE task_id = p_parent_id;
        //         END IF;
        //     END
        // ');

        // // Kreiranje trigger-a
        // DB::unprepared('
        //     CREATE TRIGGER TREE_TASK_AI
        //     AFTER INSERT ON task
        //     FOR EACH ROW
        //     BEGIN
        //         CALL UPDATE_CHILD_COUNT(NEW.parent_id);
        //     END
        // ');

        // DB::unprepared('
        //     CREATE TRIGGER TREE_TASK_AU
        //     AFTER UPDATE ON task
        //     FOR EACH ROW
        //     BEGIN
        //         CALL UPDATE_CHILD_COUNT(NEW.parent_id);
        //         CALL UPDATE_CHILD_COUNT(OLD.parent_id);
        //     END
        // ');

        // DB::unprepared('
        //     CREATE TRIGGER TREE_TASK_AD
        //     AFTER DELETE ON task
        //     FOR EACH ROW
        //     BEGIN
        //         CALL UPDATE_CHILD_COUNT(OLD.parent_id);
        //     END
        // ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // // Brisanje trigger-a
        // DB::unprepared('DROP TRIGGER IF EXISTS TREE_TASK_AI');
        // DB::unprepared('DROP TRIGGER IF EXISTS TREE_TASK_AU');
        // DB::unprepared('DROP TRIGGER IF EXISTS TREE_TASK_AD');

        // // Brisanje stored procedure
        // DB::unprepared('DROP PROCEDURE IF EXISTS UPDATE_CHILD_COUNT');

        // // Brisanje tabela u obrnutom redosledu
        // Schema::dropIfExists('task_log_action');
        // Schema::dropIfExists('task_field');
        // Schema::dropIfExists('task_child_count');
        // Schema::dropIfExists('task_action');
        // Schema::dropIfExists('task');
        // Schema::dropIfExists('profile_exec_info');
        // Schema::dropIfExists('profile_const');
        // Schema::dropIfExists('profile');
        // Schema::dropIfExists('resource');
        // Schema::dropIfExists('resource_type');
        // Schema::dropIfExists('actions');
        // Schema::dropIfExists('update_kind');
        // Schema::dropIfExists('trans_level');
        // Schema::dropIfExists('tracking_changes_method');
        // Schema::dropIfExists('resource_kind');
        // Schema::dropIfExists('log_level');
        // Schema::dropIfExists('http_method');
        // Schema::dropIfExists('execution_status');
        // Schema::dropIfExists('data_type');
        // Schema::dropIfExists('action_target');
        // Schema::dropIfExists('action_position');
        // Schema::dropIfExists('action_level');
        // Schema::dropIfExists('action_kind');
    }
};
