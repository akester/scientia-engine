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
 
class scientiaDB {
	private $configFileLocation = 'mysql.ini';
	private $db;
	
	public function __construct() {
		$path = __DIR__ . '/' . $this->configFileLocation;
		$loader = new scientiaFileLoader();
		$cfg = $loader->loadIni($path);
		$this->db = new mysqli($cfg['host'], $cfg['user'], $cfg['pass'],
				$cfg['dbname']);
		if ($this->db->connect_errno) {
			throw new ScientiaDataBaseError('Connect failed: ' . $this->db->connect_error);
		}
	}
}
 ?>