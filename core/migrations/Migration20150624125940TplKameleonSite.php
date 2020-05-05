<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

use Hubzero\Content\Migration\Base;

// No direct access
defined('_HZEXEC_') or die();

/**
 * Migration script for removing site Kameleon template
 **/
class Migration20150624125940TplKameleonSite extends Base
{
	/**
	 * Up
	 **/
	public function up()
	{
		$this->deleteTemplateEntry('kameleon', 0);
	}

	/**
	 * Down
	 **/
	public function down()
	{
		$styles = array(
			'header' => 'dark',
			'theme'  => 'salmon'
		);

		$this->addTemplateEntry('kameleon', 'kameleon (site)', 0, 1, 0, $styles);
	}
}
