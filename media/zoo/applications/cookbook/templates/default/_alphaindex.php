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

<div class="alpha-index <?php if ($this->params->get('template.alignment') == 'center') echo 'alpha-index-center'; ?>">
	<div class="alpha-index-1">
		<?php echo $this->alpha_index->render(); ?>
	</div>		
</div>