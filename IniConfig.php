<?php
namespace Paska\Config;

use Paska\FileLoader\FileDataCache\FileDataCache;
use Paska\FileLoader\IniLoader;
use Paska\PointerBag\ReadableBag;

class IniConfig extends ReadableBag {

	/**
	 * @var IniLoader
	 */
	private $loader;

	/**
	 * @param string $configDir
	 */
	public function __construct($configDir) {
		$this->loader = IniLoader::fromLocator(array($configDir));
	}

	/**
	 * @param string $file
	 * @param string|null $section
	 */
	public function load($file, $section = null) {
		$this->setData($this->doLoad($file, $section));
	}

	/**
	 * @param string $file
	 * @params string|null $section
	 */
	public function mergeFile($file, $section = null) {
		$this->mergeData($this->doLoad($file, $section));
	}

	public function mergeConfig(IniConfig $config) {

	}

	/**
	 * @param FileDataCache $cache
	 */
	public function setCache(FileDataCache $cache)
	{
		$this->loader->setCache($cache);
	}

	private function doLoad($file, $section = null) {
		if ($section) {
			return $this->loader->loadSection($file, $section);
		}

		return $this->loader->load($file);
	}
}
