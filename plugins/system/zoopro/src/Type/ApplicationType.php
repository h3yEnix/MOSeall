<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

use YOOtheme\Builder\Source;

class ApplicationType
{
    /**
     * @param Source       $source
     * @param \Application $application
     * @param string       $type
     * @param string       $categoryType
     *
     * @return array
     */
    public static function config(Source $source, $application, $type, $categoryType)
    {
        $fields = [

            'name' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'Name',
                    'filters' => ['limit'],
                ],
            ],

            'description' => [
                'type' => 'String',
                'metadata' => [
                    'label' => 'Description',
                    'filters' => ['limit'],
                ],
            ],

            'categories' => [
                'type' => [
                    'listOf' => $categoryType,
                ],
                'metadata' => [
                    'label' => 'Categories',
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::categories',
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

        $content = ParamsContentType::config($application->getParamsForm()->getXML('application-content'), $application);

        if (!empty($content['fields'])) {

            $fields['content'] = [
                'type' => "{$type}Content",
                'metadata' => [
                    'label' => 'Content',
                ],
                'extensions' => [
                    'call' => __CLASS__ . '::content',
                ],
            ];

            $source->objectType("{$type}Content", $content);
        }

        $metadata = [
            'type' => true,
            'label' => 'Application',
        ];

        return compact('fields', 'metadata');
    }

    public static function categories(\Application $application)
    {
        $tree = $application->getCategoryTree(true);
        return $tree[0]->getChildren();
    }

    public static function link(\Application $application)
    {
        return $application->app->route->frontpage($application->id);
    }

    public static function content(\Application $application)
    {
        return $application;
    }
}
