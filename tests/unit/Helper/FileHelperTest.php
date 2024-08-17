<?php

declare(strict_types=1);

namespace OCA\Bookshelf\Tests\Unit\Helper;

use OCA\Bookshelf\AppInfo\Application;
use OCA\Bookshelf\Helper\FileHelper;
use OCP\Files\File;
use OCP\Files\Folder;
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

	public function testPrependSeparatorToPath(): void {
		$normalizedPath = FileHelper::fragmentPath($this->generateTestPath(128));
		$this->assertTrue(
			str_starts_with($normalizedPath, DIRECTORY_SEPARATOR),
			'path starts with the platform specific directory separator'
		);
		$this->assertFalse(
			str_starts_with(substr($normalizedPath, 1), DIRECTORY_SEPARATOR),
			'path starts with exactly one occurrence of the platform specific directory separator'
		);
	}

	public function testGetFolderFromPath(): void {
		$app = new Application();
		$container = $app->getContainer();

		$child = $this->getMockBuilder(Folder::class)
					  ->disableOriginalConstructor()
					  ->getMock();
		$child->expects($this->once())
			  ->method('getPath')
			  ->willReturn('/path/to/folder/sub/dir/path');
		$root = $this->getMockBuilder(Folder::class)
					 ->disableOriginalConstructor()
					 ->getMock();
		$root->expects($this->once())
			 ->method('get')
			 ->with('/sub/dir/path')
			 ->willReturn($child);
		$container->registerService('RootStorage', function () use ($root) {
			return $root;
		});

		$folder = FileHelper::getFolderFromPath($root, '/sub/dir/path');
		$this->assertEquals('/path/to/folder/sub/dir/path', $folder->getPath());
	}

	public function testFolderFromPathIsFile() {
		$app = new Application();
		$container = $app->getContainer();

		$child = $this->getMockBuilder(File::class)
					  ->disableOriginalConstructor()
					  ->getMock();
		$root = $this->getMockBuilder(Folder::class)
					 ->disableOriginalConstructor()
					 ->getMock();
		$root->expects($this->once())
			 ->method('get')
			 ->with('/sub/dir/path.txt')
			 ->willReturn($child);
		$container->registerService('RootStorage', function () use ($root) {
			return $root;
		});

		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Path points to a file while folder expected');
		FileHelper::getFolderFromPath($root, '/sub/dir/path.txt');
	}

	public function testFolderFromPathNotFound() {
		$app = new Application();
		$container = $app->getContainer();

		$root = $this->getMockBuilder(Folder::class)
					 ->disableOriginalConstructor()
					 ->getMock();
		$root->expects($this->once())
			 ->method('get')
			 ->with('/sub/dir/notfound')
			 ->willThrowException(new \OCP\Files\NotFoundException('file not found'));
		$container->registerService('RootStorage', function () use ($root) {
			return $root;
		});

		$this->expectException(\OCP\Files\NotFoundException::class);
		FileHelper::getFolderFromPath($root, '/sub/dir/notfound');
	}
	private function generateTestPath(int $pathLength): string {
		$characters = 'abcdefghijklmnopqrstuvwxyz' . DIRECTORY_SEPARATOR;
		$capacity = strlen($characters);
		$testPath = '';
		$appendExtraSeparators = static::probabilityThresholdHit(35);
		$prependExtraSeparators = static::probabilityThresholdHit(25);

		for ($i = 0; $i < $pathLength; $i++) {
			$testPath .= $characters[static::randomInt(0, $capacity - 1)];
		}
		if ($appendExtraSeparators) {
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
