<?php
/**
 * @package   System - ZOO YOOtheme Pro
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

namespace YOOtheme\Builder\Joomla\Zoo\Type;

use Joomla\String\Inflector;
use function YOOtheme\app;
use YOOtheme\Str;
use YOOtheme\Zoo;

class CustomItemsQueryType
{
    /**
     * @param \Type          $type
     * @param mixed          $name
     * @param \Application[] $applications
     *
     * @return array
     */
    public static function config($name, \Type $type, $applications)
    {
        $application = $type->getApplication();
        $appOptions = array_column($applications, 'id', 'name');

        $pluralId = Inflector::getInstance()->toPlural($type->id);

        $singularLower = Str::lower($type->getName());
        $pluralLower = Str::lower(Inflector::getInstance()->toPlural($type->getName()));
        $pluralUpper = Str::titleCase(Inflector::getInstance()->toPlural($type->getName()));

        $orderOptions = [];
        foreach ($type->getElements() as $element) {
            if ($element->getMetaData('orderable') == 'true') {
                $orderOptions[$element->config->name ?: $element->getMetaData('name')] = $element->identifier;
            }
        }

        return [
            'fields' => [

                Str::camelCase(['custom', $pluralId]) => [
                    'type' => [
                        'listOf' => $name,
                    ],
                    'args' => [
                        'appid' => [
                            'type' => 'String',
                        ],
                        'categories' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                        'tags' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                        'frontpage' => [
                            'type' => 'Boolean',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        'order' => [
                            'type' => 'String',
                        ],
                        'order_direction' => [
                            'type' => 'String',
                        ],
                        'order_alphanum' => [
                            'type' => 'Boolean',
                        ],
                    ],
                    'metadata' => [
                        'label' => "Custom ZOO {$application->getMetaData('name')} {$pluralUpper}",
                        'group' => 'ZOO',
                        'fields' => [
                            'appid' => [
                                'label' => 'Application',
                                'description' => 'Only items from the selected application are loaded.',
                                'type' => 'select',
                                'options' => $appOptions,
								'defaultIndex' => 0,
                                'attrs' => [
                                    'class' => 'uk-height-small',
                                ],
                                'show' => count($appOptions) > 1,
                            ],
                            'categories' => [
                                'label' => 'Limit by Categories',
                                'description' => "{$pluralUpper} are only loaded from the selected categories. {$pluralUpper} from child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.",
								'type' => 'select',
								'default' => [],
								'options' => [
									['evaluate' => 'config.zoo[appid]["categories"]'],
								],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small uk-resize-vertical',
                                ],
                            ],
                            'tags' => [
                                'label' => 'Limit by Tags',
                                'description' => "{$pluralUpper} are only loaded from the selected tags. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple tags.",
								'type' => 'select',
								'default' => [],
								'options' => [
									['evaluate' => 'config.zoo[appid]["tags"]'],
								],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small uk-resize-vertical',
                                ],
                            ],
                            'frontpage' => [
                                'label' => "Limit by Frontpage {$pluralUpper}",
                                'type' => 'checkbox',
                                'text' => "Load frontpage {$pluralLower} only",
                            ],
                            '_offset' => [
                                'description' => "Set the starting point and limit the number of {$pluralUpper}.",
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
                                        'default' => 10,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                ],
                            ],
                            '_order' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'order' => [
                                        'label' => 'Order',
                                        'type' => 'select',
                                        'default' => '_itempublish_up',
                                        'options' => [
                                            'Published' => '_itempublish_up',
                                            'Created' => '_itemcreated',
                                            'Modified' => '_itemmodified',
                                            'Alphabetical' => '_itemname',
                                            'Hits' => '_itemhits',
                                            'Random' => '_random',
                                        ] + $orderOptions,
                                    ],
                                    'order_direction' => [
                                        'label' => 'Direction',
                                        'type' => 'select',
                                        'default' => 'DESC',
                                        'options' => [
                                            'Ascending' => 'ASC',
                                            'Descending' => 'DESC',
                                        ],
                                    ],
                                ],
                            ],
                            'order_alphanum' => [
                                'text' => 'Alphanumeric Ordering',
                                'type' => 'checkbox',
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => [
                                'type' => $type->id,
                            ],
                        ],
                    ],
                ],

            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        // extending itemTable
        return static::thru(app(Zoo::class)->table->item, function () use ($args) {

            // get database
            $db = $this->database;

            // get dates
            $date = $this->app->date->create();
            $now = $db->Quote($date->toSQL());
            $null = $db->Quote($db->getNullDate());

            $orderby = [$args['order']];
            if ($args['order_direction'] === 'DESC') {
                $orderby[] = '_reversed';
            }

            if (!empty($args['order_alphanum'])) {
                $orderby[] = '_alphanumeric';
            }

            // get item ordering
            list($join, $order) = $this->_getItemOrder($orderby, false);

            $categories = array_map('intval', $args['categories']);

            $tags = array_map(function ($tag) use ($db) {
                return $db->Quote($tag);
            }, $args['tags']);

            $query = 'SELECT a.*'
                . " FROM {$this->name} AS a"
                . ($categories ? ' JOIN ' . ZOO_TABLE_CATEGORY_ITEM . ' AS b ON a.id = b.item_id' : '')
                . (!empty($args['frontpage']) ? ' JOIN ' . ZOO_TABLE_CATEGORY_ITEM . ' AS d ON a.id = d.item_id AND d.category_id = 0' : '')
                . ($tags ? ' JOIN ' . ZOO_TABLE_TAG . ' AS c ON a.id = c.item_id' : '')
                . ($join ?: '')
                . " WHERE a.type = {$db->Quote($args['type'])}"
                . ' AND a.application_id = ' . (int) $args['appid']
                . " AND a.{$this->app->user->getDBAccessString()}"
                . ' AND a.state = 1'
                . " AND (a.publish_up = {$null} OR a.publish_up <= {$now})"
                . " AND (a.publish_down = {$null} OR a.publish_down >= {$now})"
                . ($categories ? ' AND b.category_id IN (' . implode(',', $categories) . ')' : '')
                . ($tags ? ' AND c.name IN (' . implode(',', $tags) . ')' : '')
                . ' GROUP BY a.id'
                . ($order ? " ORDER BY {$order}" : '')
                . " LIMIT {$args['offset']}, {$args['limit']}";

            return $this->_queryObjectList($query);
        });
    }

    /**
     * Calls callback in object scope and return callback's result.
     *
     * @param object   $object
     * @param \Closure $callback
     *
     * @return mixed
     */
    protected static function thru($object, \Closure $callback) {
        return call_user_func($callback->bindTo($object, $object));
    }
}
