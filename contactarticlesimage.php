<?php
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Utilities\ArrayHelper;

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
	 * @param   mixed    &$row     An object with a "text" property
	 * @param   mixed    $params   Additional parameters. See {@see PlgContentContent()}.
	 * @param   integer  $page     Optional page number. Unused. Defaults to zero.
	 *
	 * @return  boolean	True on success.
	 */
	public function onContentPrepare($context, &$row, $params, $page = 0)
	{
		$allowed_contexts = array('com_contact.contact');

		if (!in_array($context, $allowed_contexts) || empty($row->id))
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
			$intro_image 				= $images->image_intro;
			$intro_image_alt 			= $images->image_intro_alt;
			$article->introimage		= $intro_image; 
			$article->introimagealt		= $intro_image_alt; 
		}
		return true;
	}

	/**
	 * Retrieve intro image from article
	 *
	 * @param   int  $articleide Id of the article
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
	  
		$query	->select($this->db->quoteName(['id', 'images']))
				->from($this->db->quoteName('#__content'))
				->whereIn($this->db->quoteName('id'), $articleids);

		$articleimages = $this->db->setQuery($query)->loadObjectList('id');

		return $articleimages;
	}
}
