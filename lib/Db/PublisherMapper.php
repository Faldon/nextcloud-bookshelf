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
 * @method Publisher insert(Publisher $publisher)
 * @method Publisher update(Publisher $publisher)
 * @method Publisher insertOrUpdate(Publisher $publisher)
 * @phpstan-extends BaseMapper<Publisher>
 */
class PublisherMapper extends BaseMapper {

	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'bookshelf_publishers', Book::class);
	}
}
