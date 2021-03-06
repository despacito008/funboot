<?php

namespace common\helpers;

use yii\helpers\BaseArrayHelper;
use yii\helpers\Json;

/**
 * Class ArrayHelper
 * @package common\helpers
 * @author funson86 <funson86@gmail.com>
 */
class ArrayHelper extends BaseArrayHelper
{
    /**
     * 将带parent_id的数组变成递归带children的树形模式
     *
     * @param array $items
     * @param int $parentId
     * @param bool $keyId 是否用ID做key
     * @return array
     */
    public static function tree(array $items, $parentId = 0, $keyId = false)
    {
        $map = $tree = [];
        foreach ($items as &$item) {
            $item['children'] = [];
            $map[$item['id']] = &$item;
        }

        foreach ($items as &$item) {
            $parent = &$map[$item['parent_id']];
            if ($parent) {
                if ($keyId) {
                    $parent['children'][$item['id']] = &$item;
                } else {
                    $parent['children'][] = &$item;
                }
            } else {
                if ($parentId == $item['parent_id'] && $keyId) {
                    $tree[$item['id']] = &$item;
                } elseif ($parentId == $item['parent_id']) {
                    $tree[] = &$item;
                }
            }
        }

        return $tree;
    }


    /**
     * 获取当前ID的顶级ID
     * Usage: $rootId = ArrayHelper::getRootId($id, Catalog::find()->asArray()->all());
     * @param int $id  parent catalog id
     * @param array $array  catalog array list
     * @return int root catalog id
     */
    public static function getRootId($id = 0, $array = [])
    {
        if (0 == $id)
            return 0;

        foreach ((array)$array as $v) {
            if ($v['id'] == $id) {
                $parentId = $v['parent_id'];
                if (0 == $parentId)
                    return $id;
                else
                    return self::getRootId($parentId, $array);
            }
        }
    }

    /**
     * 获取当前ID的父级ID
     * Usage:
     * @param int $id 当前ID
     * @param array $array  array list
     * @return null | int
     */
    public static function getParentId($id = 0, $array = [])
    {
        if (0 == $id) {
            return null;
        }

        foreach ((array)$array as $v) {
            if ($v['id'] == $id) {
                return (int)$v['parent_id'];
            }
        }

        return null;
    }

    /**
     * 获取当前ID的父级ID和父级的父级ID等组成数组
     * @param int $id 当前ID
     * @param array $array  array list
     * @return array  catalog Id collections. eg: [2, 3, 7, 8]
     */
    public static function getParentIds($id = 0, $array = [])
    {
        if (0 == $id) {
            return null;
        }

        foreach ((array)$array as $v) {
            if ($v['id'] == $id) {
                return array_merge([(int)$v['parent_id']], self::getParentIds($v['parent_id'], $array));
            }
        }

        return null;
    }

    /**
     * Get all catalog order by parent/child with the space before child label
     * Usage: ArrayHelper::map(Catalog::get(0, Catalog::find()->asArray()->all()), 'id', 'label')
     * @param int $parentId parent catalog id
     * @param array $array catalog array list
     * @param string $subIcon
     * @param int $level catalog level, will affect $repeat
     * @param int $add times of $repeat
     * @param string $repeat symbols or spaces to be added for sub catalog
     * @return array  catalog collections
     */
    static public function getTreeIdLabel($parentId = 0, $array = [], $subIcon = '└─', $level = 0, $add = 2, $repeat = '　')
    {
        $strRepeat = '';
        // add some spaces or symbols for non top level categories
        if ($level > 1) {
            for ($j = 0; $j < $level; $j++) {
                $strRepeat .= $repeat;
            }
        }

        $newArray = array ();
        //performance is not very good here
        foreach ((array)$array as $v) {
            if ($v['parent_id'] == $parentId) {
                $item = (array)$v;
                $item['label'] = $strRepeat . $subIcon . $v['name'];
                $newArray[] = $item;

                $tempArray = self::getTreeIdLabel($v['id'], $array, $subIcon, ($level + $add), $add, $repeat);
                if ($tempArray) {
                    $newArray = array_merge($newArray, $tempArray);
                }
            }
        }
        return $newArray;
    }

    /**
     * 对数组增加key [['id => 1, 'name'=> 'a']，['id => 2, 'name'=> 'b']] 变成[1 => ['id => 1, 'name'=> 'a']，2 => ['id => 2, 'name'=> 'b']]
     * @param array $items
     * @param string $id
     * @return array
     */
    public static function mapIdData(array $items, $id = 'id')
    {
        $list = [];
        foreach ($items as $item) {
            if (isset($item[$id])) {
                $list[$item[$id]] = $item;
            }
        }

        return $list;
    }

    /**
     * 返回整数数组
     * @param $start
     * @param $end
     * @return array
     */
    public static function dropListInt($start = 0, $end = 0, $key = true, $step = 1)
    {
        $list = [];
        for ($i = $start; $i <= $end; $i += $step) {
            $key ? $list[$i] = $i : $list[] = $i;
        }
        return $list;
    }

    /**
     *  从数组中取出指定的ids
     * @param array $items
     * @param $needIds
     * @param string $id
     * @return array
     */
    public static function sliceIds(array $items, $needIds, $id = 'id')
    {
        $items = self::mapIdData($items, $id);

        $list = [];
        if (count($needIds)) {
            foreach ($needIds as $k) {
                $list[] = $items[$k];
            }
        }

        return $list;
    }

    /**
     * 获取子分类的ids
     * @param int $id
     * @param array $array 从数据表中查询的数组
     * @param string $parentId
     * @return array
     */
    static public function getChildrenIds($id = 0, $array = [], $parentId = 'parent_id')
    {
        $result[] = $id;
        foreach ((array)$array as $v) {
            if ($v[$parentId] == $id) {
                $tempResult = self::getChildrenIds($v['id'], $array, $parentId);
                if ($tempResult) {
                    $result = array_merge($result, $tempResult);
                }
            }
        }
        return $result;
    }

    /**
     * 将数组转换成整数，通过与操作
     * @param $array
     * @return int|mixed
     */
    public static function arrayToInt($array)
    {
        if (!is_array($array) || !count($array)) {
            return 0;
        }

        $value = 0;
        foreach ($array as $v) {
            $value |= $v;
        }
        return $value;
    }

    /**
     * 将整数转换成数组，通过与操作
     * @param $array
     * @return int|mixed
     */
    public static function intToArray($i = 0, $labels = [])
    {
        if (!$i) {
            return [];
        }

        $array = [];
        foreach ($labels as $k => $v) {
            if ($k & $i) {
                $array[$k] = $k;
            }
        }
        return $array;
    }

    /**
     * 获取数组指定的字段为key
     *
     * @param array $arr 数组
     * @param string $field 要成为key的字段名
     * @return array
     */
    public static function arrayKey($arr, $field)
    {
        $newArray = [];
        if (empty($arr)) {
            return $newArray;
        }

        foreach ($arr as $value) {
            isset($value[$field]) && $newArray[$value[$field]] = $value;
        }

        return $newArray;
    }

    /**
     * Get the root catalog id, then get the sub catalog of the root
     * @param int $id  parent catalog id
     * @param array $array  catalog array list
     * @return array  the sub catalog of root catalog Id collections.
     */
    static public function getRootSub2($id = 0, $array = [])
    {
        $newArray = [];
        $rootId = self::getRootId($id, $array);
        foreach ((array)$array as $v) {
            if ($v['parent_id'] == $rootId) {
                array_push($newArray, $v);
            }
        }

        return $newArray;
    }

}
