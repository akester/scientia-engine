<?php 
/*
 * Scientia - Free Knowledge
 *
 * Copyright (C) 2013 - Andrew Kester
 *
 *    This file is part of Scientia.
 *
 *    Scientia is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    Scientia is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with Scientia.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * General functions regarding the Scientia Modules
 * @author andrew
 *
 */
class scientiaModuleCommon {
	/*
	 * This *SHOULD* for both production and development cases ...
	 * But it doesn't.
	 */
	private $modulePath;
	
	public function __construct(){
		$this->modulePath = __DIR__ . '/../../scientia/modules/';
	}
	
	/**
	 * Fetches the current list of available modules.
	 */
	public function getModules() {
		/* Files that we ignore as modules */
		$ignoreFiles = array(
				'README',
				'.',
				'..'
		);
		$data = scandir($this->modulePath);
		$out = array();
		foreach ($data as $d) {
			if (!in_array($d, $ignoreFiles))
				$out[] = $d;
		}
		return $out;
	}
}
?>