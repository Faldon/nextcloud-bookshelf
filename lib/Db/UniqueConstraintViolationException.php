<?php

declare(strict_types=1);
/**
 * nextCloud - Bookshelf
 *
 * SPDX-FileCopyrightText: 2024 Thomas Pulzer <t.pulzer@thesecretgamer.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Bookshelf\Db;

class UniqueConstraintViolationException extends \Exception {
	public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}
