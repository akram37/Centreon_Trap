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

namespace CentreonTrap\Domain\Interfaces;

use CentreonTrap\Domain\Trap;

interface TrapServiceInterface
{
    /**
     * Find traps.
     *
     * @return traps[]
     */
    public function findTraps(): array;

    public function findTrap(int $id) :?Trap;

    public function createTrap(Trap $trap) :void;

    public function updateTrap(Trap $trap) :void;

    public function deleteTrap(int $id) :void;
}
