# Content - Contact intro-image for articles Plugin
Plugin for including intro images on articles from contact

## Why
On a contact page you can list the articles written by this person. But the articles list includes only title and date, no image.

## Features
With this plugin you can show the intro image of the articles. Besides this plugin you will need an override of com_contact/contact/default_articles.php

```
<?php if ($this->params->get('show_articles')) : ?>
<div class="contact-articles" itemscope itemtype="http://schema.org/Article">
	<?php foreach ($this->item->articles as $article) : ?>
		<div class="contact-article">
			<div class="contact-image">
				<?php if (isset($article->introimage) && (!empty($article->introimage))) : ?>
					<img src="<?php echo $article->introimage; ?>" alt="<?php echo $article->introimagealt; ?>" />
				<?php endif; ?>
			</div>
			<div class="contact-content">
				<p class="article-date">
					<time datetime="<?php echo JHtml::_('date', $article->publish_up, 'c'); ?>" itemprop="datePublished">
								<?php echo JText::sprintf(JHtml::_('date', $article->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
					</time>
				</p>
				<h2 class="article-title" itemprop="name">
					<?php echo JHtml::_('link', JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language)), htmlspecialchars($article->title, ENT_COMPAT, 'UTF-8')); ?>
				</h2>
				<a class="article-readmore" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language)); ?>"><?php echo JText::_('AUTHOR_READ_MORE'); ?></a>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
```

You can then use CSS to style the articles list. An example can be found [here](https://www.dr-menzel-it.de/blog/plugin-fuer-autor-seite)
