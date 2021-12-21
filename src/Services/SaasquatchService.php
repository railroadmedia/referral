<?php

namespace Railroad\Referral\Services;

use Exception;
use Railroad\Referral\Exceptions\NotFoundException;
use Railroad\Referral\Exceptions\ReferralException;
use Railroad\Referral\Exceptions\SaasquatchException;
use Railroad\Referral\Exceptions\SaasquatchUserExistsException;
use Railroad\Referral\ExternalHelpers\SaasquatchApi;
use Railroad\Referral\Models\Structures\SaasquatchUser;

class SaasquatchService
{
    /**
     * @var SaasquatchApi
     */
    protected $saasquatchApi;

    protected $saasquatchReferralProgramId;

    /**
     * SaasquatchService constructor.
     *
     * @param SaasquatchApi $saasquatchApi
     */
    public function __construct(
        SaasquatchApi $saasquatchApi
    ) {
        $this->saasquatchApi = $saasquatchApi;
        $this->saasquatchReferralProgramId = config('referral.saasquatch_referral_program_id');
    }

    /**
     * @param int $userId
     *
     * @return SaasquatchUser|null
     *
     * @throws ReferralException
     * @throws SaasquatchException
     */
    public function getUser($userId): ?SaasquatchUser
    {
        try {
            $userData = $this->saasquatchApi->getUser($userId);
//var_dump($userData);
//die("mircea-debug-7");

            return $this->hydrateSaasquatchUser($userData);
        } catch (NotFoundException $ex) {
            return null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $userId
     *
     * @return SaasquatchUser
     *
     * @throws ReferralException
     * @throws SaasquatchException
     */
    public function createOrGetUser($userId): SaasquatchUser
    {
//        var_dump($this->saasquatchApi->createUser($userId));
//        die("mircea-debug1");
        try {

            // in case user already exists:  {"errors":[{"title":"Saasquatch Exception","detail":"User already exists"}],"code":400}
            // gets out of try catch

//var_dump($this->saasquatchApi->createUser($userId));
//die("mircea-debug-3");

            // todo: if user already exists, error: {"errors":[{"title":"Saasquatch Exception","detail":"User already exists"}],"code":400}
            // todo: //update: it only happens if the data from POST is invalid ('drumeo-30-day-referral-test' instead of 'drumeo-30-day-referral-link_30-day')

            // error will be caught be try/catch and it will try to update the user!
            // if there is a different program_id than the one from .env, it will break at hydrateSaasquatchUser
            $userData = $this->saasquatchApi->createUser($userId);   // crapa aici
//dd($userData);
//var_dump($userData);
//die("saasquatch-service-4");
//            die("mircea-debug-6");

            return $this->hydrateSaasquatchUser($userData);
        } catch (SaasquatchUserExistsException $ex) {
            // if error, enters here
            // IF USER EXISTS, IT CATHCES THIS EXCEPTION!!!! IT WILL NOT APPEAR IN LOGS!!!!!!!!!

//            var_dump($this->getUser($userId));
//            die("mircea-debug2");
            return $this->getUser($userId);
        } catch (Exception $e) {
//            die("mircea-debug3");
            throw $e;
        }
    }

    /**
     * @param object $userData
     *
     * @return SaasquatchUser
     */
    public function hydrateSaasquatchUser($userData): SaasquatchUser
    {
        $userId = $userData->id;
//        $saasquatchProgramId = config('referral.saasquatch_referral_program_id');


//var_dump($userData->referralCodes);
//var_dump($this->saasquatchReferralProgramId);
//var_dump(array_key_exists($this->saasquatchReferralProgramId, $userData->referralCodes));
//var_dump(array_key_exists("drumeo-30-day-referral-test", $userData->referralCodes));
//var_dump($userData['programShareLinks']);
//var_dump($userData->programShareLinks->{$this->saasquatchReferralProgramId}->cleanShareLink);

//die("mirceadebug-saasservice");


        $referralCode = $userData->referralCodes->{$this->saasquatchReferralProgramId};
//        $referralLink = $userData->programShareLinks->{$this->saasquatchReferralProgramId}->cleanShareLink;
//        $referralLink = $userData->programShareLinks->{$this->saasquatchReferralProgramId}->cleanShareLink;
//        $referralLink = "drumeo-30-day-referral-link_30-day";  // todo: add it dynamically!!  // update: done
//        $referralLink = config('referral.customer_io_saasquatch_event_attribute');
        $referralLink = $userData->programShareLinks->{$this->saasquatchReferralProgramId}->cleanShareLink;

//die("mirceadebug-saasservice");

        return new SaasquatchUser($userId, $this->saasquatchReferralProgramId, $referralCode, $referralLink);
    }
}
