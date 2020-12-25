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

<div class="uk-grid" uk-grid>
<?php if ($item) : ?>
	<?php echo $this->renderer->render('item.teaser', array('view' => $this, 'item' => $item)); ?>
<?php endif; ?>
</div>
