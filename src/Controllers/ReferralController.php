<?php

namespace Railroad\Referral\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Railroad\Ecommerce\Contracts\UserProviderInterface;
use Railroad\Ecommerce\Repositories\ProductRepository;
use Railroad\Ecommerce\Services\UserProductService;
use Railroad\Referral\Events\EmailInvite;
use Railroad\Referral\Models\Referrer;
use Railroad\Referral\Requests\ClaimingJoinRequest;
use Railroad\Referral\Requests\EmailInviteRequest;
use Railroad\Referral\Services\ReferralService;

class ReferralController extends Controller
{
    /**
     * @var ReferralService
     */
    private $referralService;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var UserProductService
     */
    private $userProductService;

    /**
     * @param  ReferralService  $referralService
     */
    public function __construct(
        ReferralService $referralService,
        UserProviderInterface $userProvider,
        ProductRepository $productRepository,
        UserProductService $userProductService
    ) {
        $this->referralService = $referralService;
        $this->userProvider = $userProvider;
        $this->productRepository = $productRepository;
        $this->userProductService = $userProductService;
    }

    /**
     * @param  EmailInviteRequest  $request
     *
     * @return RedirectResponse
     */
    public function emailInvite(EmailInviteRequest $request)
    {
//var_dump($request);
//var_dump($request->get('link'));
//die("referral-controller-debug-1");

//"saasquatchReferralProgramId":protected]=> string(27) "drumeo-30-day-referral-test" }

        // todo: validation that email and link exists
        // do we need programId also??
        $referalUser = $this->referralService->getOrCreateReferrer(auth()->id());
//dd($referalUser); // linkul nu apare aici!!
//die("referral-controller-3");
//        $referalUser = $this->referralService->getOrCreateReferrer(auth()->id(), $request->get('link'));

        if (!$this->referralService->canRefer($referalUser)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['email-invite-message' => config('referral.messages.email_invite_fail')]);
        }

        //todo:  program_id sau event_name?   update: done
        event(new EmailInvite($referalUser->referral_link, $request->get('email')));

        $redirect = $request->has('redirect') ? $request->get('redirect') : url()->route(
            config('referral.email_invite_route')
        );

        return redirect()
            ->away($redirect)
            ->with(['email-invite-message' => config('referral.messages.email_invite_success')]);
    }

    /**
     * @param  EmailInviteRequest  $request
     *
     * @return RedirectResponse
     */
    public function claimingJoin(ClaimingJoinRequest $request)
    {
die("claiming-join-referral-controller-1");
        /**
         * @var $referrer Referrer
         */
        $referrer = Referrer::query()->where('referral_code', $request->get('referral_code'))->firstOrFail();

        $user = $this->userProvider->createUser($request->get('email'), $request->get('password'));

        if (empty($user)) {
            throw new Exception(
                'Error creating user trying to claim a referral. '.
                'Could not create user, empty response.'
            );
        }

        $productToAssign = $this->productRepository->bySku(config('referral.referral_program_product_sku'));

        if (empty($productToAssign)) {
            throw new Exception(
                'Error assigning product to user trying to claim a referral. '.
                'Could not find product with configured SKU, referral_program_product_sku.'
            );
        }

        $userProduct = $this->userProductService->createUserProduct(
            $user,
            $productToAssign,
            Carbon::now()->addDays(
                config('referral.referral_program_product_free_days')
            ),
            1
        );

        // increase the referrers referral count for this program and code and add this claimers user id to the column
        $referrer->referrals_performed += 1;

        $claimedUserIds = $referrer->claimed_user_ids;
        $claimedUserIds[] = $user->getId();
        $referrer->claimed_user_ids = $claimedUserIds;

        $referrer->save();

        // add account to saasquatch and mark their as the claimer for this referral
        // todo: from Caleb

        auth()->loginUsingId($user->getId());

        return redirect()->route(config('referral.email_invite_route'));
    }
}
