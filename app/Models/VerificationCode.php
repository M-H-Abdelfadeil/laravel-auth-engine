<?php

namespace App\Models;

use App\Enums\VerificationDriverEnum;
use App\Enums\VerificationPurposeEnum;
use App\Enums\VerificationTypeEnum;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $guarded=['id'];
    protected $casts=[
        'type'=>VerificationTypeEnum::class,
        'driver'=>VerificationDriverEnum::class,
        'purpose'=>VerificationPurposeEnum::class,

    ];
}
