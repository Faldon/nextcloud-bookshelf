<?php

declare(strict_types=1);
/**
 * nextCloud - Bookshelf
 *
 * SPDX-FileCopyrightText: 2024 Thomas Pulzer <t.pulzer@thesecretgamer.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\Bookshelf\Db;

use OCP\IDBConnection;

/**
 * Type hint base class methods
 * @method Author insert(Author $author)
 * @method Author update(Author $author)
 * @method Author insertOrUpdate(Author $author)
 * @phpstan-extends BaseMapper<Author>
 */
class AuthorMapper extends BaseMapper {

	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'bookshelf_authors', Book::class);
	}
}
