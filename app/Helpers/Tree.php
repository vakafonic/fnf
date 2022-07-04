<?php
/**
 * Created by PhpStorm.
 * User: AtrDevue
 * Date: 03.06.14
 * Time: 04:32
 */
/**
 * Класс для работы с деревом
 * @author Sam, special for www.freehabr.ru
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class Tree
{
    protected $_items = array();
    protected $_childs = array();

    /**
     * Проверка на наличие элемента
     * @param mixed $id
     * @return boolean
     */
    public function itemExists($id){
        return isset($this->_items[$id]);
    }
    /**
     * Получить кол-во элементов дерева.
     * @return integer
     */
    public function getCount(){
        return sizeof($this->_items);
    }
    /**
     * Добавление элемента в дерево
     * @param mixed $id
     * @param mixed $parent
     * @param mixed $data
     * @return void
     */
    public function addItem($id , $parent = 0 , $data)
    {
        $this->_items[$id] = array(
            'id'=>$id ,
            'parent'=>$parent ,
            'data'=>$data
        );

        if(!isset($this->_childs[$parent]))
            $this->_childs[$parent] = array();

        /*
         * Cсылка использована преднамеренно, на производительность не влияет,
         * но позволяет изменять элементы, например, при внедрении сортировки
         */
        $this->_childs[$parent][$id] = & $this->_items[$id];
    }
    /**
     * Получить элемент по индификатору
     * @param mixed $id
     * @throws Exception
     * @return array
     */
    public function getItem($id){
        if($this->itemExists($id))
            return $this->_items[$id];
        else
            throw new Exception('wrong id');
    }
    /**
     * Проверка на наличие дочерних элементов
     * @param mixed $id
     * @return boolean
     */
    public function hasChilds($id) {
        return isset($this->_childs[$id]);
    }
    /**
     * Получить дочерние элементы
     * @param mixed $id
     * @return array
     */
    public function getChilds($id){
        if(!$this->hasChilds($id))
            return array();
        return $this->_childs[$id];
    }
    /**
     * Рекурсивное удаление элементов (узел + дочерние)
     * @param mixed $id
     * @return void
     */
    protected function _remove($id){
        /*
         * Получаем дочерние элементы
         */
        $childs = $this->getChilds($id);
        if(!empty($childs)){
            /*
             * Рекурсивное удаление дочерних элементов
             */
            foreach ($childs as $k=>$v){
                $this->_remove($v['id']);
            }
        }
        /*
         * Удаляем узел элемента
         */
        if(isset($this->_childs[$id]))
            unset($this->_childs[$id]);
        /*
         * Получаем id родительского узла
         */
        $parent = $this->_items[$id]['parent'];
        /*
         * Удаляем из родительского узла ссылку на дочерний
         */
        if(!empty($this->_childs[$parent])){
            foreach ($this->_childs[$parent] as $key=>$item){
                if($item['id']==$id){
                    unset($this->_childs[$parent][$key]);
                    break;
                }
            }
        }
        /*
         *  Удаляем элемент
         */
        unset($this->_items[$id]);
    }
    /**
     * Удаление узла
     * @param mixed $id
     * @return void
     */
    public  function removeItem($id){
        if($this->itemExists($id))
            $this->_remove($id);
    }
    /**
     * Перемещение узла
     * @param mixed $id
     * @param mixed $newParent
     * @return void
     */
    public function changeParent($id , $newParent)
    {
        if($this->itemExists($id) && ($this->itemExists($newParent) || $newParent === 0))
        {
            $oldParent = $this->_items[$id]['parent'];
            $this->_items[$id]['parent'] = $newParent;
            if(!empty($this->_childs[$oldParent]))
            {
                foreach ($this->_childs[$oldParent] as $k=>$v)
                {
                    if($v['id']===$id)
                    {
                        unset($this->_childs[$oldParent][$k]);
                        break;
                    }
                }
            }
            $this->_childs[$newParent][$id] = & $this->_items[$id];
        }
    }
    //======================================
    /**
     * Тестовый метод отрисовки  узла
     * @param mixed $parent
     * @return string
     */
    protected function _createGroup($parent){
        $s='';
        $childs = $this->getChilds($parent);
        foreach ($childs as $k=>$v){
            $s.='<div style="border:1px solid #000000;padding:5px;margin:3">
                 Элемент ' . $v['id'];
            if($this->hasChilds($v['id'])){
                $s.=$this->_createGroup($v['id']);
            }
            $s.='</div>';
        }
        return $s;
    }
    /**
     * Тестовый метод отрисовки дерева
     * @param mixed $startItem — индификатор элемента, с которого необходимо начать отрисовку
     * @return string
     */
    public function createHtml($startItem){
        return $this->_createGroup($startItem);
    }
}