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

namespace CentreonTrap\Infrastructure;

use Centreon\Domain\RequestParameters\RequestParameters;
use Centreon\Infrastructure\DatabaseConnection;
use Centreon\Infrastructure\Repository\AbstractRepositoryDRB;
use Centreon\Infrastructure\RequestParameters\SqlRequestParametersTranslator;
use CentreonTrap\Domain\Trap;
use CentreonTrap\Domain\Interfaces\TrapRepositoryInterface;
use Centreon\Domain\Entity\EntityCreator;

class TrapRepositoryRDB extends AbstractRepositoryDRB implements TrapRepositoryInterface
{
    /**
     * @var SqlRequestParametersTranslator
     */
    private $sqlRequestTranslator;

    public function __construct(DatabaseConnection $db, SqlRequestParametersTranslator $sqlRequestTranslator)
    {
        $this->db = $db;
        $this->sqlRequestTranslator = $sqlRequestTranslator;
        $this->sqlRequestTranslator
            ->getRequestParameters()
            ->setConcordanceStrictMode(RequestParameters::CONCORDANCE_MODE_STRICT);
    }

    /**
     * @inheritDoc
     */
    public function findTraps(): array
    {
        $concordanceArray = [
            'id' => 'trap.id',
            'name' => 'trap.name',
            'description' => 'trap.name',
        ];

        // We only allow certain search parameters
        $this->sqlRequestTranslator->setConcordanceArray($concordanceArray);

        $request =
            'SELECT SQL_CALC_FOUND_ROWS DISTINCT traps.*
            FROM `:db`.mod_traps_objects traps';

        $request = $this->translateDbName($request);

        // get search from query parameters
        $searchRequest = $this->sqlRequestTranslator->translateSearchParameterToSql();
        $request .= !is_null($searchRequest) ? ' WHERE ' . $searchRequest : '';

        // Pagination
        $request .= $this->sqlRequestTranslator->translatePaginationToSql();

        $statement = $this->db->prepare($request);

        foreach ($this->sqlRequestTranslator->getSearchValues() as $key => $data) {
            $type = key($data);
            $value = $data[$type];
            $statement->bindValue($key, $value, $type);
        }
        $statement->execute();

        // Set the total records found
        $record = $this->db->query('SELECT FOUND_ROWS()');
        $totalRecord = (int)$record->fetchColumn();

        $this->sqlRequestTranslator->getRequestParameters()->setTotal($totalRecord);

        $traps = [];
        while (false !== ($result = $statement->fetch(\PDO::FETCH_ASSOC))) {
            $traps[] = EntityCreator::createEntityByArray(
                Trap::class,
                $result
            );
        }

        return $traps;
    }


    public function findTrap(int $id): ?Trap
    {
        $request = $this->translateDbName(
            'SELECT traps.* FROM `:db`.mod_traps_objects traps WHERE traps.id = :id'
        );

        $statement = $this->db->prepare($request);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null;
        }

        return EntityCreator::createEntityByArray(
            Trap::class,
            $result
        );
    }

    public function createTrap(Trap $trap): void
    {
        $request = $this->translateDbName(
            'INSERT INTO `:db`.mod_traps_objects (name, description) VALUES (:name, :description)'
        );

        $statement = $this->db->prepare($request);
        $statement->bindValue(':name', $trap->getName());
        $statement->bindValue(':description', $trap->getDescription());
        $statement->execute();
    }

    public function updateTrap(Trap $trap): void
    {
        $request = $this->translateDbName(
            'UPDATE `:db`.mod_traps_objects SET name = :name, description = :description WHERE id = :id'
        );

        $statement = $this->db->prepare($request);
        $statement->bindValue(':name', $trap->getName());
        $statement->bindValue(':description', $trap->getDescription());
        $statement->bindValue(':id', $trap->getId(), \PDO::PARAM_INT);
        $statement->execute();
    }

    public function deleteTrap(int $id): void
    {
        $request = $this->translateDbName(
            'DELETE FROM `:db`.mod_traps_objects WHERE id = :id'
        );

        $statement = $this->db->prepare($request);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
