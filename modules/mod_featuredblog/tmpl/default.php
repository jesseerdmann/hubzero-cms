<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2011 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if ($this->getError()) 
{
	?>
	<p class="error"><?php echo JText::_('MOD_FEATUREDBLOG_MISSING_CLASS'); ?></p>
	<?php 
} 
else if ($this->row) 
{
	$base = rtrim(JURI::getInstance()->base(true), '/');

	$yearFormat  = "Y";
	$monthFormat = "m";
	?>
	<div class="<?php echo $this->cls; ?>">
		<p class="featured-img">
			<a href="<?php echo JRoute::_('index.php?option=com_members&id=' . $this->row->created_by . '&active=blog&task=' . JHTML::_('date', $this->row->publish_up, $yearFormat) . '/' . JHTML::_('date', $this->row->publish_up, $monthFormat) . '/' . $this->row->alias); ?>">
				<img width="50" height="50" src="<?php echo $base; ?>/modules/mod_featuredblog/images/blog_thumb.gif" alt="<?php echo htmlentities(stripslashes($this->title)); ?>" />
			</a>
		</p>
		<p>
			<a href="<?php echo JRoute::_('index.php?option=com_members&id=' . $this->row->created_by . '&active=blog&task=' . JHTML::_('date', $this->row->publish_up, $yearFormat) . '/' . JHTML::_('date', $this->row->publish_up, $monthFormat) . '/' . $this->row->alias); ?>">
				<?php echo $this->escape(stripslashes($this->title)); ?>
			</a>: 
		<?php if ($this->txt) { ?>
			<?php echo \Hubzero\Utility\String::truncate(strip_tags($this->txt), $this->txt_length); ?>
		<?php } ?>
		</p>
	</div>
	<?php
}
?>