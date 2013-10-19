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
	private $overrides;
	
	public function __construct(){
		$this->modulePath = __DIR__ . '/../../scientia/modules/';
	}
	
	/**
	 * Allows a user to replace the module paths to development paths.
	 * @param array $paths
	 */
	public function overrideModulePaths($paths) {
		$this->overrides = $paths;
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
	
	/**
	 * Fetches current module names.
	 */
	public function getModuleNames() {
		$modules = $this->getModules();
		$loader = new scientiaFileLoader();
		$out = array();
		foreach($modules as $m) {
			if (array_key_exists($m, $this->overrides))
				$path = $this->overrides[$m] . '/info.ini';
			else 
				$path = $this->modulePath . $m . '/info.ini';
			
			/* If the module doesn't have an information file, just skip it. */
			if (!is_file($path))
				continue;
			$info = $loader->loadIni($path);
			if (!array_key_exists('name', $info))
				$out[] = $m;
			else
				$out[] = $info['name'];
		}
		return $out;
	}
}
?>