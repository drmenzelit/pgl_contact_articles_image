<?php
/**
 * Contact Articles Image Plugin
 *
 * @copyright  Copyright (C) 2020 Viviana Menzel. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Utilities\ArrayHelper;

/**
 * Plugin adds intro-image to articles in contact
 *
 * @since  1.0
 */
class PlgContentContactArticlesImage extends CMSPlugin
{
	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 * @since  3.3
	 */
	protected $db;

	/**
	 * Plugin that retrieves contact information for contact
	 *
	 * @param   string   $context  The context of the content being passed to the plugin.
	 * @param   mixed    $row      An object with a "text" property
	 * @param   mixed    $params   Additional parameters. See {@see PlgContentContent()}.
	 * @param   integer  $page     Optional page number. Unused. Defaults to zero.
	 *
	 * @return  boolean	True on success.
	 */
	public function onContentPrepare($context, &$row, $params, $page = 0)
	{

		if ($context != 'com_contact.contact' || empty($row->id))
		{
			return true;
		}

		$articleids 				= ArrayHelper::getColumn($row->articles, 'id');
		$articleimages       		= $this->getArticleImage($articleids);

		foreach ($row->articles as $article)
		{
			if (!isset($articleimages[$article->id]))
			{
				continue;
			}

			$images  					= json_decode($articleimages[$article->id]->images);
			$introImage 				= $images->image_intro;
			$introImageAlt	 			= $images->image_intro_alt;
			$article->introimage		= $introImage;
			$article->introimagealt		= $introImageAlt;
		}

		return true;
	}

	/**
	 * Retrieve intro image from article
	 *
	 * @param   array  $articleids Id of the articles
	 *
	 * @return  mixed|null|integer
	 */
	protected function getArticleImage($articleids)
	{
		$articleids = ArrayHelper::toInteger($articleids);
		$articleids = array_filter($articleids);

		if (empty($articleids))
		{
			return array();
		}

		$query = $this->db->getQuery(true);

		$query->select($this->db->quoteName(array('id', 'images')))
			->from($this->db->quoteName('#__content'))
			->whereIn($this->db->quoteName('id'), $articleids);

		$articleimages = $this->db->setQuery($query)->loadObjectList('id');

		return $articleimages;
	}
}
