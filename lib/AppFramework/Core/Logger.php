<?php

declare(strict_types=1);
/**
 * nextCloud - Bookshelf
 *
 * SPDX-FileCopyrightText: 2024 Thomas Pulzer <t.pulzer@thesecretgamer.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Bookshelf\AppFramework\Core;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger {
	protected string $appName;
	protected LoggerInterface $logger;

	public function __construct(string $appName, LoggerInterface $logger) {
		$this->appName = $appName;
		$this->logger = $logger;
	}

	/**
	 * Writes a message to the log file
	 * @param string $msg the message to be logged
	 * @param ?string $level the severity of the logged event, defaults to 'error'
	 */
	public function log(string $msg, ?string $level = null): void {
		$context = ['app' => $this->appName];
		match ($level) {
			LogLevel::DEBUG => $this->logger->debug($msg, $context),
			LogLevel::INFO, LogLevel::NOTICE => $this->logger->info($msg, $context),
			LogLevel::WARNING, LogLevel::ALERT => $this->logger->warning($msg, $context),
			LogLevel::EMERGENCY, LogLevel::CRITICAL => $this->logger->emergency($msg, $context),
			default => $this->logger->error($msg, $context)
		};
	}
}
