<?php

declare(strict_types=1);
/**
 * nextCloud - Bookshelf
 *
 * SPDX-FileCopyrightText: 2024 Thomas Pulzer <t.pulzer@thesecretgamer.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\Bookshelf\Utility;

use OCA\Bookshelf\AppFramework\Core\Logger;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;
use OCP\IConfig;
use OCP\PreConditionNotMetException;
use Psr\Log\LogLevel;

class Settings {
	private string $appName;
	private IConfig $manager;
	private IRootFolder $root;
	private Logger $logger;

	public function __construct(string $appName, IConfig $manager, IRootFolder $root, Logger $logger) {
		$this->appName = $appName;
		$this->manager = $manager;
		$this->root = $root;
		$this->logger = $logger;
	}

	public function setBookPath(string $userId, string $bookPath): bool {
		try {
			$home = $this->root->getUserFolder($userId);
			$fragment = $home->get($bookPath);
			if ($fragment instanceof \OCP\Files\Folder) {
				$this->manager->setUserValue($userId, $this->appName, 'path', $bookPath);
				return true;
			}
		} catch (NotPermittedException|NotFoundException|PreConditionNotMetException $e) {
			$this->logger->log($e->getMessage(), LogLevel::ERROR);
			return false;
		}
	}
}
