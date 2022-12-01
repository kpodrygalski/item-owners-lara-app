<?php

use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('address_user', function (Blueprint $table) {
            $table->foreignIdFor(Address::class, 'address_id')
                ->constrained('addresses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignIdFor(User::class, 'user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('address_user');
    }
};
