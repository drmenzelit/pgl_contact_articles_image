<?php
defined('_JEXEC') or die;

class PlgContentContactArticlesImage extends JPlugin
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

		if (!in_array($context, $allowed_contexts))
		{
			return true;
		}

		// Return if we don't have a valid article id
		if (!isset($row->id) || !(int) $row->id)
		{
			return true;
		}

		foreach ($row->articles as $article) {
			$articleimages       		= $this->getArticleImage($article->id);
			$images  					= json_decode($articleimages->images);
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
	protected function getArticleImage($articleid)
	{

		$query = $this->db->getQuery(true);

		$query->select('content.images');
		$query->from($this->db->quoteName('#__content', 'content'));
		$query->where('content.id = ' . (int) $articleid);

		$this->db->setQuery($query);

		$articleimages = $this->db->loadObject();

		return $articleimages;
	}
}
