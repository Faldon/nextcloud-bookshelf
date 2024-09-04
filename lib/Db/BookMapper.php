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
 * @method Book insert(Book $book)
 * @method Book update(Book $book)
 * @method Book insertOrUpdate(Book $book)
 * @extends BaseMapper<Book>
 */
class BookMapper extends BaseMapper {

	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'bookshelf_books', Book::class);
	}
}
