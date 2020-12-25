<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<?php if ($itemstitle) : ?>
<h2><?php echo $itemstitle; ?></h2>
<?php endif; ?>

<?php

	// init vars
	$i = 0;
	$columns = $this->params->get('template.items_cols', 2);

	// render rows
    foreach ($this->items as $key => $item) {
		if ($i % $columns == 0) echo ($i > 0 ? '</div><div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:\'.uk-panel\'}">' : '<div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:\'.uk-panel\'}">');
		echo '<div class="uk-width-medium-1-'.$columns.'">'.$this->partial('item', compact('item')).'</div>';
		$i++;
	}
	if (!empty($this->items)) {
		echo '</div>';
	}

?>

<?php echo $this->partial('pagination'); ?>