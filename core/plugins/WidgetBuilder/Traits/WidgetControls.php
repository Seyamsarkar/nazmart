<?php

namespace Plugins\WidgetBuilder\Traits;

use Plugins\PageBuilder\Fields\Textarea;

trait WidgetControls
{
    public function addField(string $field_name, array $fieldInfo){
        $namespace =  'Plugins\PageBuilder\Fields\\'.$fieldInfo['type'];
        if (class_exists($namespace)){
            $value  =  $this->setting_item($field_name);

            $field = new $namespace(  array_merge(['value' => $value],$fieldInfo));
            echo $field->render();
        }
    }

    public function start_control(){
        ob_start();
    }
    public function end_control(){
        return ob_get_clean();
    }
}
