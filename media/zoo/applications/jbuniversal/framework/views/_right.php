<?php
/**
 * JBZoo Application
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Application
 * @license    GPL-2.0
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/JBZoo
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<?php if ($helpMsg = $this->app->jbhelp->hook('cart', 'right')) : ?>
    <div class="jbinfo uk-panel uk-panel-box">
        <h3 class="jbinfo-header"><?php echo JText::_('JBZOO_CART_HELP_RIGHT') ?></h3>

        <div class="jbinfo-block-right">
            <?php echo $helpMsg; ?>
        </div>
    </div>
<?php endif;
