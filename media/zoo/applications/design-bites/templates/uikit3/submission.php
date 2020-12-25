<?php  

// no direct access
defined('_JEXEC') or die('Restricted access');

// add page title
$page_title = sprintf(($this->item->id ? JText::_('Edit %s') : JText::_('Add %s')), $this->type->name);
$this->app->document->setTitle($page_title);

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>

<div class="yoo-zoo <?php echo $css_class; ?> <?php echo $css_class.'-'.$this->submission->alias; ?>">

    <h1><?php echo $page_title;?></h1>

    <?php echo $this->partial('submission'); ?>

</div>
