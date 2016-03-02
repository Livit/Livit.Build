<?php

namespace Livit\Build;

use Illuminate\Database\Eloquent\Model;

class Editable extends Model
{
    protected $user;

    public function __construct()
    {
        $this->user = \Auth::user();
    }

    public function text($object, $column, $permissions = null, $owner_id = 0, $editable_by_owner = false, $html = '//element//')
    {
        return $this->create_element('text', $object, $column, $permissions, $owner_id, $editable_by_owner, $html);
    }

    public function textarea($object, $column, $permissions = null, $owner_id = 0, $editable_by_owner = false, $html = '//element//')
    {
        return $this->create_element('textarea', $object, $column, $permissions, $owner_id, $editable_by_owner, $html);
    }

    private function create_element($type, $object, $column, $permissions, $owner_id, $editable_by_owner, $html)
    {
        if ((isset($permissions) && $this->user->can($permissions)) ||
            ($owner_id != 0 && $owner_id == $this->user->id && $editable_by_owner)) {
            $element = '<span v-if="!appLoaded">'.$object->$column.'</span>';
            $element += '<editable-'.$type.' model="'.get_class($object).'" row-id="'.$object->id.'" key="'.$column.'" value="'.$object->$column.'"></editable-'.$type.'>';
        } elseif (!empty($object->$column)) {
            $element = '<span>'.$object->$column.'</span>';
        } else {
            return '';
        }

        return str_replace('//element//', $element, $html);
    }
}
