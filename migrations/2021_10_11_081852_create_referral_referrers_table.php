<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralReferrersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'referral_referrers',
            function (Blueprint $table) {

                $table->increments('id');
                $table->integer('user_id')->index();

                $table->string('referral_program_id', 191)->index()->nullable();
                $table->string('referral_code', 191)->index()->nullable();
                $table->string('referral_link', 191)->index()->nullable();
                $table->smallInteger('referrals_performed')->index();
                $table->text('claimed_user_ids')->nullable();

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
        Schema::dropIfExists('referral_referrers');
    }
}
