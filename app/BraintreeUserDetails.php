<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BraintreeUserDetails extends Model
{
    /**
     * A braintree row belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
