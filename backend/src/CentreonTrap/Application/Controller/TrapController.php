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

use CentreonTrap\Domain\Interfaces\TrapServiceInterface;
use Centreon\Domain\RequestParameters\Interfaces\RequestParametersInterface;
use CentreonTrap\Domain\Trap;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function findTrap(int $id): View
    {
        $trap = $this->trapService->findTrap($id);

        if ($trap === null) {
            return $this->view(['message' => 'Trap not found'], Response::HTTP_NOT_FOUND);
        }

        $context = (new Context())->setGroups(['trap_main']);

        return $this->view($trap)->setContext($context);
    }

    public function createTrap(Request $request): View
    {
        $data = json_decode($request->getContent(), true);

        // Validate the data (basic validation, expand as needed)
        if (!isset($data['name']) || !isset($data['description'])) {
            return $this->view(['status' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        $trap = new Trap();
        $trap->setName($data['name']);
        $trap->setDescription($data['description']);

        // Assuming createTrap method returns the created trap entity or throws an exception
        try {
            $this->trapService->createTrap($trap);
        } catch (\Exception $e) {
            return $this->view(['status' => 'Error creating trap', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->view(['status' => 'Trap created'], Response::HTTP_CREATED);
    }

    public function updateTrap(int $id, Request $request): View
    {
        $data = json_decode($request->getContent(), true);
        $trap = new Trap();
        $trap->setId($id);
        $trap->setName($data['name']);
        $trap->setDescription($data['description']);

        $trap = $this->trapService->findTrap($id);
        if ($trap === null) {
            return $this->view(['message' => 'Trap not found'], Response::HTTP_NOT_FOUND);
        }

        $this->trapService->updateTrap($trap);

        return $this->view(['status' => 'Trap updated'], Response::HTTP_OK);
    }

    public function deleteTrap(int $id): View
    {
        $trap = $this->trapService->findTrap($id);
        if ($trap === null) {
            return $this->view(['message' => 'Trap not found'], Response::HTTP_NOT_FOUND);
        }

        $this->trapService->deleteTrap($id);
        return $this->view(['status' => 'Trap deleted'], Response::HTTP_NO_CONTENT);
    }

}
