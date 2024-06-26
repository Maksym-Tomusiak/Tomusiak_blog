<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\Fortify;

return new class extends Migration
{
/*
* Run the migrations.
*/
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')
                ->after('password')
                ->nullable();

            $table->text('two_factor_recovery_codes')
                ->after('two_factor_secret')
                ->nullable();

            if (Fortify::confirmsTwoFactorAuthentication()) {
                $table->timestamp('two_factor_confirmed_at')
                    ->after('two_factor_recovery_codes')
                    ->nullable();
            }
        });
    }

/*
* Reverse the migrations.
*/
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [
                'two_factor_secret',
                'two_factor_recovery_codes',
            ];

            if (Fortify::confirmsTwoFactorAuthentication()) {
                $columnsToDrop[] = 'two_factor_confirmed_at';
            }

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
