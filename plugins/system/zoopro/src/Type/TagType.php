<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

class TagType
{
    /**
     * @return array
     */
    public static function config()
    {
        $fields = [

            'name' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'Name',
                    'filters' => ['limit'],
                ],
            ],

            'link' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'Link',
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::link',
                ],
            ],

        ];

        $metadata = [
            'type' => true,
            'label' => 'Tag',
        ];

        return compact('fields', 'metadata');
    }

    public static function link($tag)
    {
//        return $this->zoo->route->tag($tag->application_id, $tag->name);
    }
}
