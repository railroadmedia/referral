<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'referral_customers',
            function (Blueprint $table) {

                $table->increments('id');
                $table->integer('usora_id')->index();

                $table->smallInteger('user_referrals_performed')->index();
                $table->string('user_referral_link', 191)->index()->nullable();

                $table->timestamp('created_at')->nullable()->index();
                $table->timestamp('updated_at')->nullable()->index();
                $table->timestamp('deleted_at')->nullable()->index();

            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referral_customers');
    }
}
