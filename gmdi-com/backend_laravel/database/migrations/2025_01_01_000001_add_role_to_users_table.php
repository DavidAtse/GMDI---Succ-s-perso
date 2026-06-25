<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['chef_communication','agent_communication','responsable_rs','admin'])
                      ->default('agent_communication')->after('email');
            });
        }
    }
    public function down(): void { Schema::table('users', fn($t) => $t->dropColumnIfExists('role')); }
};
