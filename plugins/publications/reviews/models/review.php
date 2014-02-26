<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2013 Purdue University. All rights reserved.
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
 * @copyright Copyright 2005-2013 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_publications' . DS . 'tables' . DS . 'review.php');
require_once(__DIR__ . '/comment.php');

/**
 * Publications review mdoel
 */
class PublicationsModelReview extends \Hubzero\Base\Model
{
	/**
	 * ResourcesReview
	 * 
	 * @var object
	 */
	protected $_tbl_name = 'PublicationReview';

	/**
	 * Model context
	 * 
	 * @var string
	 */
	protected $_context = 'com_publications.review.comment';

	/**
	 * USer
	 * 
	 * @var object
	 */
	private $_creator = NULL;

	/**
	 * \Hubzero\Base\ItemList
	 * 
	 * @var object
	 */
	private $_comments = NULL;

	/**
	 * Commen count
	 * 
	 * @var integer
	 */
	private $_comments_count = NULL;

	/**
	 * Returns a reference to a blog comment model
	 *
	 * @param      mixed $oid ID (int) or alias (string)
	 * @return     object
	 */
	static function &getInstance($oid=0)
	{
		static $instances;

		if (!isset($instances)) 
		{
			$instances = array();
		}

		if (!isset($instances[$oid])) 
		{
			$instances[$oid] = new self($oid);
		}

		return $instances[$oid];
	}

	/**
	 * HAs this comment been reported
	 * 
	 * @return     boolean True if reported, False if not
	 */
	public function isReported()
	{
		if ($this->get('reports', -1) > 0)
		{
			return true;
		}
		// Reports hasn't been set
		if ($this->get('reports', -1) == -1) 
		{
			if (is_file(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_support' . DS . 'tables' . DS . 'reportabuse.php')) 
			{
				include_once(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_support' . DS . 'tables' . DS . 'reportabuse.php');
				$ra = new ReportAbuse($this->_db);
				$val = $ra->getCount(array(
					'id'       => $this->get('id'), 
					'category' => 'review'
				));
				$this->set('reports', $val);
				if ($this->get('reports') > 0)
				{
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Return a formatted timestamp
	 * 
	 * @param      string $as What format to return
	 * @return     boolean
	 */
	public function created($as='')
	{
		switch (strtolower($as))
		{
			case 'date':
				return JHTML::_('date', $this->get('created'), JText::_('DATE_FORMAT_HZ1'));
			break;

			case 'time':
				return JHTML::_('date', $this->get('created'), JText::_('TIME_FORMAT_HZ1'));
			break;

			default:
				return $this->get('created');
			break;
		}
	}

	/**
	 * Get the creator of this entry
	 * 
	 * Accepts an optional property name. If provided
	 * it will return that property value. Otherwise,
	 * it returns the entire JUser object
	 *
	 * @return     mixed
	 */
	public function creator($property=null)
	{
		if (!($this->_creator instanceof \Hubzero\User\Profile))
		{
			$this->_creator = \Hubzero\User\Profile::getInstance($this->get('user_id'));
		}
		if ($property)
		{
			$property = ($property == 'id') ? 'uidNumber' : $property;
			if ($property == 'picture')
			{
				return $this->_creator->getPicture($this->get('anonymous'));
			}
			return $this->_creator->get($property);
		}
		return $this->_creator;
	}

	/**
	 * Get a list or count of comments
	 * 
	 * @param      string  $rtrn    Data format to return
	 * @param      array   $filters Filters to apply to data fetch
	 * @param      boolean $clear   Clear cached data?
	 * @return     mixed
	 */
	public function replies($rtrn='list', $filters=array(), $clear=false)
	{
		if (!isset($filters['item_id']))
		{
			$filters['item_id'] = $this->get('id');
		}
		if (!isset($filters['item_type']))
		{
			$filters['item_type'] = 'review';
		}
		if (!isset($filters['parent']))
		{
			$filters['parent'] = 0;
		}

		switch (strtolower($rtrn))
		{
			case 'count':
				if (!isset($this->_comments_count) || !is_numeric($this->_comments_count) || $clear)
				{
					$this->_comments_count = 0;

					if (!$this->_comments) 
					{
						$c = $this->comments('list', $filters);
					}
					foreach ($this->_comments as $com)
					{
						$this->_comments_count++;
						if ($com->replies()) 
						{
							foreach ($com->replies() as $rep)
							{
								$this->_comments_count++;
								if ($rep->replies()) 
								{
									$this->_comments_count += $rep->replies()->total();
								}
							}
						}
					}
				}
				return $this->_comments_count;
			break;

			case 'list':
			case 'results':
			default:
				if (!($this->_comments instanceof \Hubzero\Base\ItemList) || $clear)
				{
					$tbl = new \Hubzero\Item\Comment($this->_db);

					if ($this->get('replies', null) !== null)
					{
						$results = $this->get('replies');
					}
					else
					{
						$results = $tbl->find($filters);
					}

					if ($results)
					{
						foreach ($results as $key => $result)
						{
							$results[$key] = new PublicationsModelComment($result);
						}
					}
					else
					{
						$results = array();
					}
					$this->_comments = new \Hubzero\Base\ItemList($results);
				}
				return $this->_comments;
			break;
		}
	}

	/**
	 * Get the content of the entry
	 * 
	 * @param      string  $as      Format to return state in [text, number]
	 * @param      integer $shorten Number of characters to shorten text to
	 * @return     string
	 */
	public function content($as='parsed', $shorten=0)
	{
		$as = strtolower($as);

		switch ($as)
		{
			case 'parsed':
				if (($content = $this->get('comment_parsed')))
				{
					if ($shorten)
					{
						$content = \Hubzero\Utility\String::truncate($content, $shorten, array('html' => true));
					}
					return $content;
				}

				$config = array(
					'option'   => $this->get('option', JRequest::getCmd('option')),
					'scope'    => 'review',
					'pagename' => $this->get('resource_id'),
					'pageid'   => 0,
					'filepath' => '',
					'domain'   => ''
				);

				$content = stripslashes($this->get('comment'));
				$this->importPlugin('content')->trigger('onContentPrepare', array(
					$this->_context,
					&$this,
					&$config
				));

				$this->set('comment_parsed', $this->get('comment'));
				$this->set('comment', $content);

				if ($this->get('comment_parsed', null) != null)
				{
					return $this->content($as, $shorten);
				}
				
			break;

			case 'clean':
				$content = strip_tags($this->content('comment_parsed'));
			break;

			case 'raw':
			default:
				$content = stripslashes($this->get('comment'));
				$content = preg_replace('/^(<!-- \{FORMAT:.*\} -->)/i', '', $content);
			break;
		}
		
		if ($shorten)
		{
			$content = \Hubzero\Utility\String::truncate($content, $shorten, $options);
		}
		return $content;
	}

	/**
	 * Generate and return various links to the entry
	 * Link will vary depending upon action desired, such as edit, delete, etc.
	 * 
	 * @param      string $type The type of link to return
	 * @return     string
	 */
	public function link($type='')
	{
		if (!isset($this->_base))
		{
			$this->_base = 'index.php?option=com_publications&id=' . $this->get('item_id') . '&active=reviews';
		}
		$link = $this->_base;

		// If it doesn't exist or isn't published
		switch (strtolower($type))
		{
			case 'edit':
				$link .= '&action=edit&comment=' . $this->get('id');
			break;

			case 'delete':
				$link .= '&action=delete&comment=' . $this->get('id');
			break;

			case 'reply':
				$link .= '&action=reply&category=review&refid=' . $this->get('id');
			break;

			case 'report':
				$link = 'index.php?option=com_support&task=reportabuse&category=itemcomment&id=' . $this->get('id') . '&parent=' . $this->get('item_id');
			break;

			case 'permalink':
			default:
				$link .= '#c' . $this->get('id');
			break;
		}

		return $link;
	}
}

