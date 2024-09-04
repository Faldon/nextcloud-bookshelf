<?php

declare(strict_types=1);
/**
 * nextCloud - Bookshelf
 *
 * SPDX-FileCopyrightText: 2024 Thomas Pulzer <t.pulzer@thesecretgamer.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\Bookshelf\Db;

/**
 * @method Author setCreated(\DateTime $created)
 * @method Author setUpdated(\DateTime $created)
 * @extends BaseEntity<Author>
 */
class Author extends BaseEntity {

	protected string $userId;
	protected string $givenNames;
	protected string $familyName;
	protected ?\DateTime $dateOfBirth;
	protected ?\DateTime $dateOfDeath;

	public function __construct() {
		$this->addType('user_id', 'string');
		$this->addType('given_names', 'string');
		$this->addType('family_name', 'string');
		$this->addType('date_of_birth', 'datetime');
		$this->addType('date_of_death', 'datetime');

		$this->dateOfBirth = null;
		$this->dateOfDeath = null;
	}

	public function getUserId(): string {
		return $this->userId;
	}

	public function setUserId(string $userId): Author {
		$this->userId = $userId;
		return $this;
	}

	public function getGivenNames(): string {
		return $this->givenNames;
	}

	public function setGivenNames(string $givenNames): Author {
		$this->givenNames = $givenNames;
		return $this;
	}

	public function getFamilyName(): string {
		return $this->familyName;
	}

	public function setFamilyName(string $familyName): Author {
		$this->familyName = $familyName;
		return $this;
	}

	public function getDateOfBirth(): ?\DateTime {
		return $this->dateOfBirth;
	}

	public function setDateOfBirth(\DateTime $dateOfBirth): Author {
		$this->dateOfBirth = $dateOfBirth;
		return $this;
	}

	public function getDateOfDeath(): ?\DateTime {
		return $this->dateOfDeath;
	}

	public function setDateOfDeath(\DateTime $dateOfDeath): Author {
		$this->dateOfDeath = $dateOfDeath;
		return $this;
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize(): mixed {
		return [
			'id' => $this->id,
			'user_id' => $this->userId,
			'given_names' => $this->givenNames,
			'family_name' => $this->familyName,
			'date_of_birth' => $this->dateOfBirth?->format('Y-m-d'),
			'date_of_death' => $this->dateOfDeath?->format('Y-m-d'),
		];
	}


	public static function getProperties(): array {
		return [
			'id',
			'userId',
			'givenNames',
			'familyName',
			'dateOfBirth',
			'dateOfDeath',
		];
	}
}
