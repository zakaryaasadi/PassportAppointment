<?php

namespace App\Models\Enum;

class ResponseStatusEnum
{
    const Success = 200;
    const Failed = 4000;
    const Pending = 2010;
    const SuccessBut = 2020;
    const NotFound = 4040;
}
