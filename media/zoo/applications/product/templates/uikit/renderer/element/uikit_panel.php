<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// create label
$label = '';
if (isset($params['showlabel']) && $params['showlabel']) {
	$label .= '<h3 class="uk-panel-title">';
	$label .= ($params['altlabel']) ? $params['altlabel'] : $element->config->get('name');
	$label .= '</h3>';
}

// create class attribute
$class = 'element element-'.$element->getElementType();

?>

<div class="uk-panel uk-panel-box <?php echo $class; ?>">
	<?php echo $label; ?>
	<?php echo $element->render($params); ?>
</div>