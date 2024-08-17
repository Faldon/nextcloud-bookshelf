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
use OCA\Bookshelf\Helper\FileHelper;
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

	/**
	 * Set the root path of the book library
	 * @param string $userId This library's owner
	 * @param string $bookPath The path
	 * @return bool True on success, else False
	 */
	public function setBookPath(string $userId, string $bookPath): bool {
		try {
			$home = $this->root->getUserFolder($userId);
			$fragment = $home->get($bookPath);
			if ($fragment instanceof \OCP\Files\Folder) {
				$path = FileHelper::fragmentPath($bookPath);
				$path = FileHelper::normalizePath($path);
				$this->manager->setUserValue($userId, $this->appName, 'path', $path);
				return true;
			}
			return false;
		} catch (NotPermittedException|NotFoundException|PreConditionNotMetException $e) {
			$this->logger->log($e->getMessage(), LogLevel::ERROR);
			return false;
		}
	}

	/**
	 * Get the root path of the books library
	 * @param string $userId The library's owner
	 * @return string The library's root path
	 */
	public function getBookPath(string $userId): string {
		return $this->manager->getUserValue($userId, $this->appName, 'path') ?: DIRECTORY_SEPARATOR;
	}
}
