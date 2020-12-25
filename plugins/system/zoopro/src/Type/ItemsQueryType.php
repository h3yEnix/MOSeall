<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

use Joomla\String\Inflector;
use YOOtheme\Str;

class ItemsQueryType
{
    /**
     * @param string $name
     * @param \Type  $type
     * @param \Application[] $applications
     *
     * @return array
     */
    public static function config($name, \Type $type, $applications)
    {
        $pluralId = Inflector::getInstance()->toPlural($type->id);
        $pluralLabel = Str::titleCase(Inflector::getInstance()->toPlural($type->getName()));

        return [
            'fields' => [

                Str::camelCase($pluralId) => [
                    'type' => [
                        'listOf' => $name,
                    ],
                    'args' => [
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                    ],
                    'metadata' => [
                        'label' => $pluralLabel,
                        'view' => array_reduce($applications, function($views, $application) {
                            return array_merge($views, [
                                "com_zoo.{$application->id}.category",
                                "com_zoo.{$application->id}.frontpage",
                                "com_zoo.{$application->id}.tag"
                            ]);
                        }, []),
                        'group' => 'Page',
                        'fields' => [
                            '_offset' => [
                                'description' => 'Set the starting point and limit the number of articles.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'attrs' => [
                                            'min' => 1,
                                            'required' => true,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => 'Quantity',
                                        'type' => 'limit',
                                        'attrs' => [
                                            'placeholder' => 'No limit',
                                            'min' => 0,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],

            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        $args += [
            'offset' => 0,
            'limit' => null,
        ];

        if (isset($root['items'])) {

            $items = $root['items'];

            if ($args['offset'] || $args['limit']) {
                $items = array_slice($items, (int) $args['offset'], (int) $args['limit'] ?: null);
            }

            return $items;
        }
    }
}
