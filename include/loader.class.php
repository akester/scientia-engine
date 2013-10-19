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
class scientiaFileLoader {
	/**
	 * Loads the path into memory.
	 * @param string $path The file to load.
	 * @throws ScientiaFileNotFound
	 * @return array
	 */
	public function loadIni($path) {
		if (!is_file($path))
			throw new ScientiaFileNotFound($path + ' was not found.');
		return parse_ini_file($path);
	}
}
?>