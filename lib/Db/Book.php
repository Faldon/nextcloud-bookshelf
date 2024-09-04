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
 * @method Book setCreated(\DateTime $created)
 * @method Book setUpdated(\DateTime $created)
 * @extends BaseEntity<Book>
 */
class Book extends BaseEntity {
	protected string $userId;

	protected string $title;
	protected ?string $abstract;
	protected ?string $isbn;
	protected ?\DateTime $releaseDate;
	protected ?int $edition;

	protected ?string $cover;

	public function __construct() {
		$this->addType('user_id', 'string');
		$this->addType('title', 'string');
		$this->addType('abstract', 'string');
		$this->addType('isbn', 'string');
		$this->addType('release_date', 'datetime');
		$this->addType('edition', 'integer');
		$this->addType('cover', 'blob');

		$this->abstract = null;
		$this->isbn = null;
		$this->releaseDate = null;
		$this->edition = null;
		$this->cover = null;
	}

	public function getUserId(): string {
		return $this->userId;
	}

	public function setUserId(string $userId): Book {
		$this->userId = $userId;
		return $this;
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function setTitle(string $title): Book {
		$this->title = $title;
		return $this;
	}

	public function getAbstract(): ?string {
		return $this->abstract;
	}

	public function setAbstract(string $abstract): Book {
		$this->abstract = $abstract;
		return $this;
	}

	public function getIsbn(): ?string {
		return $this->isbn;
	}

	public function setIsbn(string $isbn): Book {
		$this->isbn = $isbn;
		return $this;
	}

	public function getReleaseDate(): ?\DateTime {
		return $this->releaseDate;
	}

	public function setReleaseDate(\DateTime $releaseDate): Book {
		$this->releaseDate = $releaseDate;
		return $this;
	}

	public function getEdition(): ?int {
		return $this->edition;
	}

	public function setEdition(int $edition): Book {
		$this->edition = $edition;
		return $this;
	}

	public function getCover(): ?string {
		return $this->cover;
	}

	public function setCover(string $cover): Book {
		$this->cover = $cover;
		return $this;
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize(): mixed {
		return [
			'id' => $this->id,
			'user_id' => $this->userId,
			'given_names' => $this->title,
			'family_name' => $this->abstract,
			'isbn' => $this->isbn,
			'release_date' => $this->releaseDate?->format('Y-m-d'),
			'edition' => $this->edition,
			'cover' => base64_encode($this->cover ?? ''),
		];
	}

	public static function getProperties(): array {
		return [
			'id',
			'userId',
			'givenNames',
			'familyName',
			'isbn',
			'releaseDate',
			'edition',
			'cover',
		];
	}
}
