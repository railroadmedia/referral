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

                $table->increments('usora_id');

                $table->smallInteger('user_referrals_performed')->index();

                $table->timestamp('created_at')->index();
                $table->timestamp('updated_at')->index();

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
        Schema::dropIfExists('referrals');
    }
}
