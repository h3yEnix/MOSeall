<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// register ElementRepeatable class
App::getInstance('zoo')->loader->register('ElementRepeatable', 'elements:repeatable/repeatable.php');

/*
   Class: ElementGroup
   The group element class
*/
class ElementGroup extends ElementRepeatable {

    /*
       Function: getValue
            Returns the element's value.

       Parameters:
            $params - render parameter

        Returns:
            Value
    */
    public function _getValue($params = array()) {
        return $this->_item->elements->find("{$this->identifier}.{$this->key()}", array());
    }

    /*
        Function: getSearchData
            Get elements search data.

        Returns:
            String - Search data
    */
    public function _getSearchData() {
        return join(' ', $this->_getValue());
    }

    /*
        Function: render
            Renders the repeatable element.

       Parameters:
            $params - render parameter

        Returns:
            String - html
    */
    protected function _render($params = array()) {

        $result = array();
        $values = $this->_getValue($params);
        foreach ($this->config->get('element', array()) as $element) {

            if (empty($values[$element['name']])) {
                continue;
            }

            if ($element['type'] == 'image') {
                $result[] = "<img src=\"{$values[$element["name"]]}\">";
            } else {
                $result[] = $values[$element['name']];
            }
        }

        return $this->app->element->applySeparators('tag=[<div>%s</div>]', $result);
    }

    /*
       Function: _edit
           Renders the repeatable edit form field.

       Returns:
           String - html
    */
    protected function _edit() {

        $this->app->document->addScript('elements:image/edit.js');

        return $this->renderLayout($this->getLayout('edit.php'));
    }

    /*
       Function: editElement
          Renders elements elements for form input.

       Parameters:
          $var - form var name
          $num - option order number

       Returns:
          Array
    */
    public function editElement($var, $num, $name = null, $type = null) {
        $options = array();
        foreach (parent::getConfigForm()->getXML('_default')->xpath('param[@name="element"]/option') as $option) {
            $options[] = array('text' => JText::_((string) $option), 'value' => $option->attributes()->name);
        }

        return $this->renderLayout($this->app->path->path("elements:group/tmpl/editelement.php"), compact('var', 'num', 'name', 'type', 'options'));
    }

    /*
        Function: getConfigForm
            Get parameter form object to render input form.

        Returns:
            Parameter Object
    */
    public function getConfigForm() {
        return parent::getConfigForm()->addElementPath(dirname(__FILE__));
    }

    /*
        Function: loadAssets
            Load elements css/js config assets.

        Returns:
            Void
    */
    public function loadConfigAssets() {
        $this->app->document->addScript('elements:group/group.js');
        $this->app->document->addStylesheet('elements:group/group.css');
        return parent::loadConfigAssets();
    }

}
