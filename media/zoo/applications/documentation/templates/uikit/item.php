<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// include syntaxhighlighter
$this->app->document->addScript($this->template->resource.'libraries/prettify/prettify.js');
$css = $this->params->get('template.prettify_style', 'prettify.css');
$this->app->document->addStylesheet($this->template->resource.'libraries/prettify/'.$css);

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-'.$this->item->alias; ?>">
<?php if ($this->item->canEdit()) : ?>
    <?php $edit_link = $this->app->route->submission($this->item->getApplication()->getItemEditSubmission(), $this->item->type, null, $this->item->id, 'itemedit'); ?>
    <div class="uk-align-right">
        <a href="<?php echo JRoute::_($edit_link); ?>" title="<?php echo JText::_('Edit Item'); ?>" class="item-icon edit-item"><?php echo JText::_('Edit Item'); ?></a>
    </div>
<?php endif; ?>

	<?php if ($this->renderer->pathExists('item/'.$this->item->type)) : ?>
		<?php echo $this->renderer->render('item.'.$this->item->type.'.full', array('view' => $this, 'item' => $this->item)); ?>
		<?php echo $this->app->comment->renderComments($this, $this->item); ?>
	<?php else : ?>
		<?php echo $this->renderer->render('item.full', array('view' => $this, 'item' => $this->item)); ?>
		<?php echo $this->app->comment->renderComments($this, $this->item); ?>
	<?php endif; ?>

	<script type="text/javascript">
		jQuery(function($) { prettyPrint(); });
	</script>

</div>