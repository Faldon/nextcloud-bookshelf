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
 * @method Publisher setCreated(\DateTime $created)
 * @method Publisher setUpdated(\DateTime $created)
 * @extends BaseEntity<Publisher>
 */
class Publisher extends BaseEntity {
	protected string $userId;
	protected string $name;

	public function __construct() {
		$this->addType('user_id', 'string');
		$this->addType('name', 'string');
	}

	public function getUserId(): string {
		return $this->userId;
	}

	public function setUserId(string $userId): Publisher {
		$this->userId = $userId;
		return $this;
	}

	public function getName(): string {
		return $this->name;
	}

	public function setName(string $name): Publisher {
		$this->name = $name;
		return $this;
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize(): mixed {
		return [
			'id' => $this->id,
			'user_id' => $this->userId,
			'name' => $this->name,
		];
	}

	public static function getProperties(): array {
		return [
			'id',
			'userId',
			'name',
		];
	}
}
