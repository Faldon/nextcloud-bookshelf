<?php

declare(strict_types=1);

namespace OCA\Bookshelf\Helper;

use PHPUnit\Framework\TestCase;

class FileHelperTest extends TestCase {
	public function testAppendSeparatorToPath(): void {
		$normalizedPath = FileHelper::normalizePath($this->generateTestPath(128));
		$this->assertTrue(
			str_ends_with($normalizedPath, DIRECTORY_SEPARATOR),
			'path ends with the platform specific directory separator'
		);
		$this->assertFalse(
			str_ends_with(substr($normalizedPath, 0, -1), DIRECTORY_SEPARATOR),
			'path ends with exactly one occurrence of the platform specific directory separator'
		);
	}

	private function generateTestPath(int $pathLength): string {
		$characters = 'abcdefghijklmnopqrstuvwxyz'.DIRECTORY_SEPARATOR;
		$capacity = strlen($characters);
		$testPath = '';
		$appendExtraSeparators = static::probabilityThresholdHit(35);
		$prependExtraSeparators = static::probabilityThresholdHit(25);

		for ($i = 0; $i < $pathLength; $i++) {
			$testPath .= $characters[static::randomInt(0, $capacity - 1)];
		}
		if($appendExtraSeparators) {
			for ($i = 0; $i < static::randomInt(1, 5); $i++) {
				$testPath .= DIRECTORY_SEPARATOR;
			}
		}
		if ($prependExtraSeparators) {
			for ($i = 0; $i < static::randomInt(1, 3); $i++) {
				$testPath = DIRECTORY_SEPARATOR . $testPath;
			}
		}
		return $testPath;
	}

	private static function probabilityThresholdHit(int $percentage): bool {
		return static::randomInt(0, 100) <= $percentage;
	}

	private static function randomInt(int $min, int $max): int {
		try {
			return random_int($min, $max);
		} catch (\Exception) {
			return mt_rand($min, $max);
		}
	}
}
