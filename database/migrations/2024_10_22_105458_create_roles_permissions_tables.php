<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesPermissionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create Roles Table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'admin', 'user', 'editor'
            $table->timestamps();
        });

        // Create Permissions Table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'create-post', 'delete-user'
            $table->timestamps();
        });

        // Create Role_User Pivot Table (Many-to-Many between Users and Roles)
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique(); // Ensuring the foreign key column is a string
            $table->foreign('user_id')->references('it_emp_id')->on('it_employees')->onDelete('cascade');

            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->timestamps();
        });

        // Create Permission_Role Pivot Table (Many-to-Many between Permissions and Roles)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id'); // Use string if your permission IDs are strings
            $table->unsignedBigInteger('role_id');       // Use string if your role IDs are strings
            $table->timestamps();

            // Manually define foreign key constraints
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the tables in reverse order
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
}
