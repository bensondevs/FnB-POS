<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\Socilite\VendorType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socialite_users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // User owner of the record
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            // Vendor and token informations
            $table->tinyInteger('vendor')
                ->default(VendorType::Google);
            $table->string('token');

            // Stored user instance returned from
            // the vendor
            $table->json('user_instance')
                ->nullable();

            $table->timestamps();
            $table->timestamp('last_logged_in_at');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socialite_users');
    }
};
