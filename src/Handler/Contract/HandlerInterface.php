<?php

/*************************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Ruben Hazenbosch <rh@braune-digital.com>, Braune Digital GmbH
 *
 *  All rights reserved
 ************************************************************************/

declare(strict_types=1);

namespace App\Handler\Contract;

use App\Messenger\External\ExternalMessage;

interface HandlerInterface
{
    public function supports(ExternalMessage $externalMessage): bool;
}