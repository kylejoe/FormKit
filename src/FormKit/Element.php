<?php
namespace FormKit;
use CascadingAttribute;

abstract class Element extends CascadingAttribute
{

    /**
     * @var array class name
     */
    public $class = array();



    /**
     * Children elements
     */
    public $children = array();


    /**
     * @var array id field
     */
    public $id = array();


    public function addClass($class)
    {
        $this->class[] = $class;
        return $this;
    }

    public function addId($id)
    {
        $this->id[] = $id;
        return $this;
    }

    public function addChild($child)
    {
        $this->children[] = $child;
        return $this;
    }

    protected function _renderChildren()
    {
        return join("\n",array_map(function($item) { 
            return $item->render() . PHP_EOL;
        }, $this->children ));
    }

    protected function _renderAttributes($keys) 
    {
        $html = '';
        foreach( $keys as $key ) {
            if( $val = $this->$key ) {
                if( is_array($val) ) {

                    // check if array is a indexed array, check keys of array[0..cnt] 
                    //
                    // if it's an indexed array
                    // for attributes like "class", which the parameter can be array('class1','class2')
                    // this render the attribute as "class1 class2"
                    //
                    // if it's an associative array
                    // for attribute "style", which the parameter can be array( 'border' => '1px solid #ccc' )
                    // this render the attribute as "border: 1px solid #ccc;"
                    if( array_keys($val) === range(0, count($val)-1) ) {
                        $val = join(' ', $val);
                    } else {
                        $val0 = $val;
                        $val = '';
                        foreach( $val0 as $name => $data ) {
                            $val .= "$name:$data;";
                        }
                    }
                }
                elseif ( is_bool($val) ) {
                    $val = $key;
                }

                // for boolean values like readonly attribute, 
                // we render it as readonly="readonly".
                $html .= sprintf(' %s="%s"', 
                        $key, 
                        htmlspecialchars( $val )
                );
            }
        }
        return $html;
    }

    abstract public function render( $attributes = array() );

    public function __toString()
    {

        return $this->render();
    }
}

