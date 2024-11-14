<?php

namespace App\Traits;

trait CustomFieldTrait
{
    public function renderField($field,$value=null)
    {
        $html = '';
        $label = htmlspecialchars($field->label_name);
        $is_required= ($field->is_required=='yes') ? 'required' : '';
        switch ($field->field_type) {
            case 'text':
                $html .= "<label>{$label}</label>";
                $html .= "<input type='text' name='{$field->field_name}' class='form-control' value='{$value}' {$is_required} />";
                break;
            case 'radio':
                $opts = explode(',', $field->field_option);
                $html .= "<label>{$label}</label>"; 
                $html .= "<br>"; 
                foreach ($opts as $option) {
                    $checked= ($value==$option) ? "checked" : ""; 
                    $html .= "<label><input type='radio' name='{$field->field_name}' value='{$option}' {$checked} {$is_required} > {$option}</label> ";
                }
                break;
            case 'checkbox':
                $checkBoxValue = explode(',', $value);
                $html .= "<label>{$label}</label>";
                $html .= "<br>"; 
                $opts = explode(',', $field->field_option);
                foreach ($opts as $option) {
                    $checked= in_array($option,$checkBoxValue) ? "checked" : ""; 
                    $html .= "<label><input type='checkbox' name='{$field->field_name}[]' value='{$option}' {$checked}> {$option}</label> ";
                } 
                break;
            case 'dropdown':
                $html .= "<label>{$label}</label>";
                $html .= "<select name='{$field->field_name}' class='form-control' {$is_required}>";
                $html .= "<option value=''>---Select---</option>";
                $opts = explode(',', $field->field_option);
                foreach ($opts as $option) {
                    $selected= $option==$value ? "selected" : ""; 
                    $html .= "<option value='{$option}' {$selected}>{$option}</option>";
                }
                $html .= "</select>";
                break;
                case 'textarea':
                $html .= "<label>{$label}</label>";
                $html .= "<textarea name='{$field->field_name}' class='form-control' {$is_required}>    {$value}</textarea>";
                break;  case 'file':
                $html .= "<label>{$label}</label>";
                $html .= "<input type='file' name='{$field->field_name}' class='form-control' {$is_required} accept='image/*' />";
                if ($value) {
                    $imageUrl = url('uploads', $value);
                    $html .= "<div>
                                <a href='{$imageUrl}' target='_blank'>
                                    <img src='{$imageUrl}' alt='File preview' height='100px' width='100px'>
                                </a>
                              </div>";
                }
                break;
    }
        return $html;
    }
}


?>
