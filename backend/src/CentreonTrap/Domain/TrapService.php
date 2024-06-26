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

namespace CentreonTrap\Domain;

use CentreonTrap\Domain\Interfaces\TrapServiceInterface;
use CentreonTrap\Domain\Interfaces\TrapRepositoryInterface;

class TrapService implements TrapServiceInterface
{
    /**
     * @var TrapRepositoryInterface
     */
    private $trapRepository;

    /**
     * TrapService constructor.
     * @param TrapRepositoryInterface $trapRepository
     */
    public function __construct(TrapRepositoryInterface $trapRepository)
    {
        $this->trapRepository = $trapRepository;
    }

    /**
     * @inheritDoc
     */
    public function findTraps(): array
    {
        return $this->trapRepository->findTraps();
    }

    public function findTrap(int $id): ?Trap
    {
        return $this->trapRepository->findTrap($id);
    }

    public function createTrap(Trap $trap): void
    {
         $this->trapRepository->createTrap($trap);
    }

    public function updateTrap(Trap $trap): void
    {
        $this->trapRepository->updateTrap($trap);
    }

    public function deleteTrap(int $id): void
    {
        $this->trapRepository->deleteTrap($id);
    }


}
