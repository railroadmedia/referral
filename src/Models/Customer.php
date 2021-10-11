<?php

namespace Railroad\Referral\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Customer
 *
 * @package Railroad\Referral\Models
 *
 * @property integer $usora_id
 * @property integer $user_referrals_performed
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'referral_customers';

    protected $primaryKey = 'usora_id';

    /**
     * Customer constructor.
     */
    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('referral.database_connection_name'));

        parent::__construct($attributes);
    }
}
