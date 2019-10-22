<?php


namespace megaoffice\client\traits;


trait NestedStructuresTrait
{
    public static function createRecursiveList($data, $nestedKey = 'items'){
        $n = 0;
        return static::buildList($data, 0, $n, $nestedKey);
    }

    /**
     * @param $items
     * @param $parent
     * @param $n
     * @return array
     */
    protected static function buildList(&$items, $parent, &$n, $nestedKey){

        $children = [];
        for(;$n<count($items);){
            if($items[$n]['parent_id'] == $parent){
                $i = $items[$n];
                $n++;
                $i[$nestedKey]  = self::buildList($items, $i['id'], $n, $nestedKey);
                $children[] = $i;
            }else{
                break;
            }
        }
        return $children;
    }

    public static function createRecursiveListWithContent($data, $content, $nestedKey = 'items'){
        $n = 0;
        $contentId = 0;
        return static::buildListWithContent($data, 0, $n, $content, $contentId, $nestedKey);
    }

    /**
     * @param $items
     * @param $parent
     * @param $n
     * @return array
     */
    protected static function buildListWithContent(&$items, $parent, &$n, $content, &$contentId, $nestedKey){

        $children = [];
        for(;$n<count($items);){
            if($items[$n]['parent_id'] == $parent){
                $i = $items[$n];
                $i['products'] = [];
                foreach($content as $key=>$c) {
                    if ($items[$n]['id'] == $c['category_id']) {
                        $i['products'][] = $c;
                        unset($content[$key]);
                    }
                }
                $n++;
                $i[$nestedKey]  = self::buildListWithContent($items, $i['id'], $n, $content,$contentId, $nestedKey);
                $children[] = $i;
            }else{
                break;
            }
        }
        return $children;
    }



}