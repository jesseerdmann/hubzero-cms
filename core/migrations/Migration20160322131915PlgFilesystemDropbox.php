<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

use Hubzero\Content\Migration\Base;

/**
 * Migration script for adding the dropbox filesystem plugin
 **/
class Migration20160322131915PlgFilesystemDropbox extends Base
{
	/**
	 * Up
	 **/
	public function up()
	{
		$this->addPluginEntry('filesystem', 'dropbox', 0);
	}

	/**
	 * Down
	 **/
	public function down()
	{
		$this->deletePluginEntry('filesystem', 'dropbox');
	}
}
