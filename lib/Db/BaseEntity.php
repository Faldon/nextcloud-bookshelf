<?php

declare(strict_types=1);
/**
 * nextCloud - Bookshelf
 *
 * SPDX-FileCopyrightText: 2024 Thomas Pulzer <t.pulzer@thesecretgamer.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\Bookshelf\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @template T
 */
abstract class BaseEntity extends Entity implements \JsonSerializable {
	protected string $userId;
	protected \DateTime $created;
	protected \DateTime $updated;

	public function getCreated(): \DateTime {
		return $this->created;
	}

	public function setCreated(\DateTime $created): BaseEntity {
		$this->created = $created;
		return $this;
	}

	public function getUpdated(): \DateTime {
		return $this->updated;
	}

	public function setUpdated(\DateTime $updated): BaseEntity {
		$this->updated = $updated;
		return $this;
	}

	abstract public static function getProperties(): array;
	abstract public function jsonSerialize(): mixed;
}
