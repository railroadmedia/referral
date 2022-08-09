<?php

namespace Railroad\Referral\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
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
use Railroad\Referral\Services\SaasquatchService;

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
     * @var SaasquatchService
     */
    private $saasquatchService;

    /**
     * @param  ReferralService  $referralService
     */
    public function __construct(
        ReferralService $referralService,
        UserProviderInterface $userProvider,
        ProductRepository $productRepository,
        UserProductService $userProductService,
        SaasquatchService $saasquatchService
    ) {
        $this->referralService = $referralService;
        $this->userProvider = $userProvider;
        $this->productRepository = $productRepository;
        $this->userProductService = $userProductService;
        $this->saasquatchService = $saasquatchService;
    }

    /**
     * @param  EmailInviteRequest  $request
     *
     * @return JsonResponse|RedirectResponse
     */
    public function emailInvite(EmailInviteRequest $request)
    {
        $brand = $request->get('brand');
        $referrer = $this->referralService->getOrCreateReferrer(
            user()->id,
            config('referral.saasquatch_referral_program_id.' . $brand),
            $brand
        );

        if (!$this->referralService->canRefer($referrer)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['email-invite-message' => config('referral.messages.email_invite_fail')]);
        }

        // this event is used in other packages to actually send the email
        event(new EmailInvite($request->get('email'), $referrer->referral_link, $brand));

        $redirect = $request->has('redirect') ? $request->get('redirect') : url()->route(
            config('referral.email_invite_redirect_route'),
             ['brand' => $request->get('brand')]
        );

        // this endpoint can handle json requests for the mobile app as well
        if ($request->isJson()) {
            return response()
                ->json(['success' => true, 'email-invite-message' => config('referral.messages.email_invite_success')]);
        }

        return redirect()
            ->away($redirect)
            ->with(['email-invite-message' => config('referral.messages.email_invite_success')]);
    }

    /**
     * @param  ClaimingJoinRequest  $request
     *
     * @return JsonResponse|RedirectResponse
     */
    public function claimingJoin(ClaimingJoinRequest $request)
    {
        /**
         * @var $referrer Referrer
         */
        $referrer = Referrer::query()->where('referral_code', $request->get('referral_code'))->firstOrFail();

        if (empty($referrer)) {
            throw new Exception(
                'Error finding referrer for referral code: ' . $request->get('referral_code'));
        }


        if (!$this->referralService->canRefer($referrer)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['email-invite-message' => config('referral.messages.email_invite_fail')]);
        }

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

        $this->saasquatchService->applyReferralCode($user->getId(), $referrer->referral_code, $request->get('brand'));

        auth()->loginUsingId($user->getId());

        // this endpoint can handle json requests for the mobile app as well
        if ($request->isJson()) {
            return response()
                ->json(['success' => true, 'claiming_user_id' => $user->getId()]);
        }

        return redirect()->route(config('referral.claim_redirect_route'), ['brand' => $request->get('brand')]);
    }
}
