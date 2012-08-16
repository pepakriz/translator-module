<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace TranslatorModule\Extraction\Filters;

use Venne;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class LatteFilter extends BaseFilter
{

	/**
	 * @param $file
	 * @return array
	 */
	public function extract($file)
	{
		if(substr($file, -6) !== '.latte'){
			return NULL;
		}

		$content = file_get_contents($file);

		$ret = $this->matchBetween($content, "{_'", "'}");

		return $ret;
	}
}
