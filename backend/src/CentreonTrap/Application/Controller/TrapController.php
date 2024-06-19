<?php

/*
 * Copyright 2020 Centreon (http://www.centreon.com/)
 *
 * Centreon is a full-fledged industry-strength solution that meets
 * the needs in IT infrastructure and application monitoring for
 * service performance.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,*
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
declare(strict_types=1);

namespace CentreonTrap\Application\Controller;

use CentreonDummy\Domain\Interfaces\TrapServiceInterface;
use Centreon\Domain\RequestParameters\Interfaces\RequestParametersInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;

/**
 * This class is designed to manage all API REST requests concerning the dummies
 *
 * @package CentreonTrap\Application\Controller
 */
class TrapController extends AbstractFOSRestController
{
    /**
     * @var TrapServiceInterface
     */
    private $trapService;

    /**
     * TrapController constructor.
     *
     * @param TrapServiceInterface $trapService
     */
    public function __construct(TrapServiceInterface $trapService)
    {
        $this->trapService = $trapService;
    }

    /**
     * Entry point to find dummies.
     *
     * @param RequestParametersInterface $requestParameters
     * @return View
     */
    public function findTraps(RequestParametersInterface $requestParameters): View
    {
        $traps = $this->trapService->findtraps();

        $context = (new Context())->setGroups(['trap_main']);

        return $this->view([
            'result' => $traps,
            'meta' => $requestParameters->toArray(),
        ])->setContext($context);
    }
}
