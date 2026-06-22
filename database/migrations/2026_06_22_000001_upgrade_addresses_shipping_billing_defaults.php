<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add new columns
        Schema::table('addresses', function (Blueprint $table) {
            $table->boolean('is_default_shipping')->default(false)->after('country');
            $table->boolean('is_default_billing')->default(false)->after('is_default_shipping');
        });

        // Step 2: Migrate data — promote any existing defaults to both roles
        DB::table('addresses')->where('is_default', true)->update([
            'is_default_shipping' => true,
            'is_default_billing'  => true,
        ]);

        // Step 3: Drop old index, then old column, then add new indexes
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_default']); // drops addresses_user_id_is_default_index
            $table->dropColumn('is_default');
            $table->index(['user_id', 'is_default_shipping'], 'addresses_user_shipping_idx');
            $table->index(['user_id', 'is_default_billing'],  'addresses_user_billing_idx');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->boolean('is_default')->default(false)->after('country');
        });

        DB::table('addresses')->where('is_default_shipping', true)->update(['is_default' => true]);

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex('addresses_user_shipping_idx');
            $table->dropIndex('addresses_user_billing_idx');
            $table->dropColumn(['is_default_shipping', 'is_default_billing']);
            $table->index(['user_id', 'is_default']);
        });
    }
};
