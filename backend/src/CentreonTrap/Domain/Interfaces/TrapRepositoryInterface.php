<?php

/*
 * Centreon
 *
 * Source Copyright 2005-2019 Centreon
 *
 * Unauthorized reproduction, copy and distribution
 * are not allowed.
 *
 * For more information : contact@centreon.com
 *
 */
declare(strict_types=1);

namespace CentreonTrap\Domain\Interfaces;

use CentreonTrap\Domain\Trap;

interface TrapRepositoryInterface
{
    /**
     * Find all traps.
     *
     * @return Traps[]
     */
    public function findTraps(): array;

    public function findTrap(int $id) :?Trap;

    public function createTrap(Trap $trap) :void;

    public function updateTrap(Trap $trap) :void;

    public function deleteTrap(int $id) :void;
}
