<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'addresses';

    // Composite index created by the original addresses table migration.
    // On MySQL this index is what satisfies the FK on user_id, so it can't
    // be dropped until a standalone index on user_id exists to take over.
    private const OLD_INDEX = 'addresses_user_id_is_default_index';

    // Standalone index that keeps the user_id foreign key satisfied once
    // OLD_INDEX is dropped.
    private const FK_SUPPORT_INDEX = 'addresses_user_id_foreign_support_index';

    private const SHIPPING_INDEX = 'addresses_user_shipping_idx';
    private const BILLING_INDEX = 'addresses_user_billing_idx';

    public function up(): void
    {
        // Step 1: Add new columns (idempotent)
        Schema::table(self::TABLE, function (Blueprint $table) {
            if (! Schema::hasColumn(self::TABLE, 'is_default_shipping')) {
                $table->boolean('is_default_shipping')->default(false)->after('country');
            }
            if (! Schema::hasColumn(self::TABLE, 'is_default_billing')) {
                $table->boolean('is_default_billing')->default(false)->after('is_default_shipping');
            }
        });

        // Step 2: Migrate data — promote any existing defaults to both roles
        if (Schema::hasColumn(self::TABLE, 'is_default')) {
            DB::table(self::TABLE)->where('is_default', true)->update([
                'is_default_shipping' => true,
                'is_default_billing'  => true,
            ]);
        }

        // Step 3a: Create a standalone index on user_id BEFORE touching the
        // old composite index. MySQL refuses to drop
        // addresses_user_id_is_default_index while it's the only index
        // covering the user_id foreign key, so a replacement must exist
        // first. This has to be its own statement — not merged into the
        // same Schema::table() call as the drop below — so the replacement
        // index is physically committed before MySQL evaluates the drop.
        if (! Schema::hasIndex(self::TABLE, self::FK_SUPPORT_INDEX)) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->index('user_id', self::FK_SUPPORT_INDEX);
            });
        }

        // Step 3b: Drop the old composite index — now safe, because
        // FK_SUPPORT_INDEX covers the foreign key on user_id.
        if (Schema::hasIndex(self::TABLE, self::OLD_INDEX)) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->dropIndex(self::OLD_INDEX);
            });
        }

        // Step 3c: Drop the old column
        if (Schema::hasColumn(self::TABLE, 'is_default')) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->dropColumn('is_default');
            });
        }

        // Step 3d: Add the new shipping/billing indexes
        Schema::table(self::TABLE, function (Blueprint $table) {
            if (! Schema::hasIndex(self::TABLE, self::SHIPPING_INDEX)) {
                $table->index(['user_id', 'is_default_shipping'], self::SHIPPING_INDEX);
            }
            if (! Schema::hasIndex(self::TABLE, self::BILLING_INDEX)) {
                $table->index(['user_id', 'is_default_billing'], self::BILLING_INDEX);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn(self::TABLE, 'is_default')) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->boolean('is_default')->default(false)->after('country');
            });
        }

        if (Schema::hasColumn(self::TABLE, 'is_default_shipping')) {
            DB::table(self::TABLE)->where('is_default_shipping', true)->update(['is_default' => true]);
        }

        // Restore the original composite index BEFORE dropping the
        // shipping/billing/support indexes below, so user_id never loses
        // FK coverage on MySQL at any point in the rollback.
        if (! Schema::hasIndex(self::TABLE, self::OLD_INDEX)) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->index(['user_id', 'is_default'], self::OLD_INDEX);
            });
        }

        if (Schema::hasIndex(self::TABLE, self::SHIPPING_INDEX)) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->dropIndex(self::SHIPPING_INDEX);
            });
        }

        if (Schema::hasIndex(self::TABLE, self::BILLING_INDEX)) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->dropIndex(self::BILLING_INDEX);
            });
        }

        // FK_SUPPORT_INDEX is redundant now that OLD_INDEX is back (both
        // cover user_id), and OLD_INDEX already exists above, so this is
        // safe to drop.
        if (Schema::hasIndex(self::TABLE, self::FK_SUPPORT_INDEX)) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->dropIndex(self::FK_SUPPORT_INDEX);
            });
        }

        Schema::table(self::TABLE, function (Blueprint $table) {
            if (Schema::hasColumn(self::TABLE, 'is_default_shipping')) {
                $table->dropColumn('is_default_shipping');
            }
            if (Schema::hasColumn(self::TABLE, 'is_default_billing')) {
                $table->dropColumn('is_default_billing');
            }
        });
    }
};
