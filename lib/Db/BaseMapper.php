<?php

declare(strict_types=1);
/**
 * nextCloud - Bookshelf
 *
 * SPDX-FileCopyrightText: 2024 Thomas Pulzer <t.pulzer@thesecretgamer.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Bookshelf\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @template EntityType of BaseEntity
 */
abstract class BaseMapper {

	protected string $tableName;
	protected string $tableAlias;
	protected string $entityClass;
	protected IDBConnection $db;

	public function __construct(IDBConnection $db, string $tableName, ?string $entityClass = null) {
		$this->tableName = $tableName;
		$this->entityClass = $entityClass;
		$this->db = $db;
		$this->tableAlias = str_replace('bookshelf_', '', $tableName);
	}

	/**
	 * Find entity by user
	 * @returns BaseEntity
	 * @throws DoesNotExistException If the entity does not exist
	 * @throws MultipleObjectsReturnedException If more than one entity exists
	 * @throws Exception
	 * @phpstan-return EntityType
	 */
	public function find(string $userId, int $id): BaseEntity {
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from($this->tableName, $this->tableAlias)
			->where(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId))
			)
			->andWhere(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			);
		$result = $qb->executeQuery()->fetchAll();
		if (empty($result)) {
			throw new DoesNotExistException('entity does not exist');
		}
		if (count($result) > 1) {
			throw new MultipleObjectsReturnedException('multiple objects returned');
		}
		return BaseEntity::fromRow((array)$result[0]);
	}

	/**
	 * Find all entities by user
	 * @returns BaseEntity[]
	 * @throws Exception
	 * @phpstan-return EntityType[]
	 */
	public function findAll(string $userId): array {
		$result = [];
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from($this->tableName, $this->tableAlias)
			->where(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId))
			);
		/** @psalm-suppress MixedAssignment */
		foreach ($qb->executeQuery()->fetchAll() as $row) {
			$result[] = BaseEntity::fromRow((array)$row);
		}
		return $result;
	}

	/**
	 * @param $entity BaseEntity
	 * @return BaseEntity
	 * @throws Exception
	 * @throws UniqueConstraintViolationException
	 * @phpstan-param EntityType $entity
	 * @phpstan-return EntityType
	 */
	public function insert(BaseEntity $entity) : BaseEntity {
		$now = new \DateTime();
		if (method_exists($entity, 'setCreated')) {
			$entity->setCreated($now);
		}
		$qb = $this->db->getQueryBuilder();
		$properties = $entity::getProperties();
		$qb->insert($this->tableName)
			->values(array_fill_keys($properties, '?'))
			->setParameters(array_values($properties));
		try {
			$qb->executeStatement();
			return $entity;
		} catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
			throw new UniqueConstraintViolationException($e->getMessage(), $e->getCode(), $e);
		} catch (Exception $e) {
			if ($e->getReason() === Exception::REASON_UNIQUE_CONSTRAINT_VIOLATION) {
				throw new UniqueConstraintViolationException($e->getMessage(), $e->getCode(), $e);
			}
			throw $e;
		}
	}

	/**
	 * @param $entity BaseEntity
	 * @return BaseEntity
	 * @throws Exception
	 * @phpstan-param EntityType $entity
	 * @phpstan-return EntityType
	 */
	public function update(BaseEntity $entity) : BaseEntity {
		$now = new \DateTime();
		if (method_exists($entity, 'setUpdated')) {
			$entity->setUpdated($now);
		}
		$qb = $this->db->getQueryBuilder()
						->update($this->tableName);
		/** @var string[] $properties */
		$properties = call_user_func($this->entityClass .'::getProperties');
		/** @psalm-suppress MixedArgumentTypeCoercion */
		foreach ($properties as $propertyName) {
			$getProperty = 'get'.ucfirst($propertyName);
			$qb = $qb->set($propertyName, $entity->$getProperty());
		}
		$qb = $qb->where(
			$qb->expr()->eq('id', $qb->createNamedParameter($entity->getId()))
		);
		$qb->executeStatement();
		return $entity;
	}

	/**
	 * @param $entity BaseEntity
	 * @return BaseEntity
	 * @throws Exception
	 * @phpstan-param EntityType $entity
	 * @phpstan-return EntityType
	 */
	public function insertOrUpdate(BaseEntity $entity) : BaseEntity {
		try {
			return $this->insert($entity);
		} catch (UniqueConstraintViolationException $e) {
			return $this->update($entity);
		}
	}
}
