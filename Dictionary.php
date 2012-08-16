<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TranslatorModule;

use Venne;
use Nette\Object;
use Nette\Utils\Finder;
use TranslatorModule\Dictionaries\Drivers\IDriver;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class Dictionary extends Object implements IDictionary
{

	/** @var string */
	protected $lang;

	/** @var string */
	protected $path;

	/** @var array */
	protected $data;


	/**
	 * @param $path
	 */
	public function __construct($path)
	{
		$this->path = $path;
	}


	/**
	 * Translates the given string.
	 * @param  string   message
	 * @param  int      plural count
	 * @return string
	 */
	public function translate($message, $count = NULL)
	{
		$this->loadData();

		if (isset($this->data[$message]) && $count === NULL) {
			return $this->data[$message];
		}

		if (isset($this->data[$message]) && $count !== NULL) {
			return $this->data[$message][$count];
		}

		return $message;
	}


	/**
	 * @param string $lang
	 */
	public function setLang($lang)
	{
		$this->lang = $lang;
	}


	/**
	 * Load data from files.
	 *
	 * @return array
	 */
	public function getData()
	{
		if (!file_exists($this->path)) {
			throw new \Nette\InvalidArgumentException("Path '{$this->path}' does not exists.");
		}

		$data = array();

		foreach (Finder::findFiles("*.{$this->lang}.*")->in($this->path) as $file) {
			$file = $file->getPathname();
			$ex = explode('.', $file);

			$class = "\\TranslatorModule\\Drivers\\" . ucfirst($ex[2]) . "Driver";

			/** @var $driver IDriver */
			$driver = new $class($file);
			$data = $data + $driver->load();
		}

		return $data;
	}
}