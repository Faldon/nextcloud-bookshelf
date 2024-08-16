<?php

declare(strict_types=1);
/**
 * nextCloud - Bookshelf
 *
 * SPDX-FileCopyrightText: 2024 Thomas Pulzer <t.pulzer@thesecretgamer.de>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\Bookshelf\Helper;

class FileHelper {
	/**
	 * Terminate any given path fragment with the platform directory separator
	 * @param  string  $pathFragment The path to normalize
	 * @return string A path terminated by exactly one occurrence of the platform specific directory separator
	 */
	public static function normalizePath(string $pathFragment): string {
        $pathFragment = preg_replace('~^(.*)/+$~U',"$1", $pathFragment);
		if(str_ends_with($pathFragment, DIRECTORY_SEPARATOR) === false) {
			$pathFragment .= DIRECTORY_SEPARATOR;
		}
		return $pathFragment;
	}

	/**
	 * Prepend any given path with the platform directory separator
	 * @param  string  $path The path to fragment
	 * @return string A path leading with exactly one occurrence of the platform specific directory separator
	 */
	public static function fragmentPath(string $path): string {
		if(str_starts_with($path, DIRECTORY_SEPARATOR) === false) {
			$path = DIRECTORY_SEPARATOR . $path;
		}
		return $path;
	}
}
