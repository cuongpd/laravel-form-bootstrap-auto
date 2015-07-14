<?php

namespace App\Models;

use Form;

class FormManager {

    function __construct() {

    }

    static public function Build($form = [ ]) {
        $form_name = 'form_' . time() . rand(10, 99);
        $form_html = Form::open([
                    'name' => $form_name,
                    'method' => 'POST',
                    'files' => false,
                    'style' => 'padding:10px;',
                    'class' => 'form-horizontal',
                    'id' => 'html5Form ' . $form_name,
        ]);
        $form_html .= Form::hidden('cform_url', \Request::url(), array( 'id' => 'cform_url' ));
        $form_html .= Form::hidden('cform_name', $form_name, array( 'id' => 'cform_name' ));

        foreach( $form as $name => $item ) {
            $placeholder = $title = isset($item['title']) ? $item['title'] : self::name2title($name);
            $value = $item['value'];
            $type = $item['type'];
            $required = 'required';
            $max_str = 0;

            if( $type == 'hidden' ) {
                $form_html .= self::getInputHidden($name, $value);
            }
            if( $type == 'password' ) {
                $form_html .= self::getInputPassword($name, $value, $title, $placeholder, $required);
            }
            if( $type == 'keyword' ) {
                $form_html .= self::getInputKeyword($name, $value, $title, $placeholder);
            }
            if( $type == 'select' ) {
                $list = [ ];
                $form_html .= self::getInputSelect($name, $value, $item['list'], $title, $placeholder);
            }
            if( $type == 'text' ) {
                if( in_array($name, ['title', 'name', 'category' ]) ) {
                    $max_str = 70;
                }
                $form_html .= self::getInputText($name, $value, $title, $placeholder, $required, $max_str);
            }
            if( $type == 'readonly' ) {
                $form_html .= self::getInputTextReadOnly($name, $value, $title);
            }
            if( $type == 'file' ) {
                $form_html .= self::getInputFile($name, $value, $title);
            }
            if( $type == 'editer' ) {
                $form_html .= self::getInputTextareaEdit($name, $value, $title);
            }
            if( $type == 'textarea' ) {
                if( in_array($name, ['description', 'sort_text' ]) ) {
                    $max_str = 200;
                }
                $form_html .= self::getInputTextarea($name, $value, $title, $max_str);
            }
            if($type == 'datetime'){
                $form_html .= self::getInputDateTime($name, $value, $title);
            }
        }
        $form_html .= '<div class="form-group" style="margin:10px;">';
        $form_html .= '<div class="col-sm-12">' . Form::submit('Submit', ['class' => 'btn btn-info', 'type' => 'button' ]) . '</div>';
        $form_html .= '</div>';

        $form_html .= Form::close();
        return $form_html;
    }

    static function name2title($column = '') {
        return ucwords(preg_replace('/_/', ' ', $column));
    }

    static function getInputHidden($name, $value) {
        return Form::hidden($name, $value, array( 'id' => $name ));
    }

    static function getInputDateTime($name = '', $value = '', $title = '') {
        $input_html = '<div class="form-group clearfix">';
        $input_html .= '<label class="col-sm-2 control-label" for="' . $name . '">' . $title . '</label>';
        $input_html .= '<div class="col-sm-4">
                <div class="input-group date form_datetime" data-date="'. date('Y-m-d' , time() ).'" data-date-format="dd MM yyyy - HH:ii p">
                    <input type="text" name="' . $name . '"  value="' . $value . '" id="' . $name . '" class="form-control">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
                <script type="text/javascript">
                    $(".form_datetime").datetimepicker({
                        weekStart: 1,
                        todayBtn:  1,
                        autoclose: 1,
                        todayHighlight: 1,
                        startView: 2,
                        forceParse: 0,
                        showMeridian: 1
                    });
                </script>';

        $input_html .= '</div>';
        $input_html .= '</div>';
        return $input_html;
    }

    static function getInputSelect($name = '', $value = '', $list = array(), $title = '') {
        $input_html = '<div class="form-group clearfix">';
        $input_html .= '<label class="col-sm-2 control-label" for="' . $name . '">' . $title . '</label>';
        $input_html .= '<div class="col-sm-6">';
        $input_html .= Form::select($name, $list, $value, array( 'class' => 'form-control selectpicker', 'id' => 'form-chosen-select' ));
        $input_html .= '</div>';
        $input_html .= '</div>';
        return $input_html;
    }

    static function getInputKeyword($name = '', $value = '', $title = '', $placeholder = '') {
        $input_html = '<div class="form-group">';
        $input_html .= '<label class="col-sm-2 control-label" for="' . $name . '">' . $title . '</label>';
        $input_html .= '<div class="col-sm-10">';
        $input_html .= '<input type="text" data-role="tagsinput" name="' . $name . '"  value="' . $value . '" placeholder="' . $placeholder . '" id="' . $name . '" class="form-control">';
        $input_html .= '</div>';
        $input_html .= '</div>';
        return $input_html;
    }

    static function getInputPassword($name = '', $value = '', $title = '', $placeholder = '', $required = 'required') {
        $input_html = '<div class="form-group">';
        $input_html .= '<label class="col-sm-2 control-label" for="' . $name . '">' . $title . '</label>';
        $input_html .= '<div class="col-sm-10">';
        $input_html .= '<input ' . $required . ' type="password" name="' . $name . '"  value="' . $value . '" placeholder="' . $placeholder . '" id="' . $name . '" class="form-control">';
        $input_html .= '</div>';
        $input_html .= '</div>';
        return $input_html;
    }

    static function getInputText($name = '', $value = '', $title = '', $placeholder = '', $required = 'required', $max_str = 0) {
        $input_html = '<div class="form-group">';
        $input_html .= '<label class="col-sm-2 control-label" for="' . $name . '">' . $title . '</label>';
        $input_html .= '<div class="col-sm-10">';
        $input_html .= '<input ' . $required . ' type="text" name="' . $name . '"  value="' . $value . '" placeholder="' . $placeholder . '" id="' . $name . '" class="form-control">';
        if( $max_str ) {
            $input_html .= '<span class="help-block m-b-none"> <span class="' . $name . '_count " > 0 </span> /' . $max_str . ' characters</span>';
            $input_html .= '<script>jQuery(document).ready(function () {jQuery(".' . $name . '_count").html((jQuery("#' . $name . '").val().length)); });</script>';
            $input_html .= '<script>jQuery("#' . $name . '").keyup(function (event) {CountUpText(jQuery(this), ' . $max_str . ');});</script>';
        }
        $input_html .= '</div>';
        $input_html .= '</div>';
        return $input_html;
    }

    static function getInputTextReadOnly($name, $value, $title) {
        $input_html = '<div class="form-group">';
        $input_html .= '<label class="col-sm-2 control-label" for="' . $name . '">' . $title . '</label>';
        $input_html .= '<div class="col-sm-10">';
        $input_html .= '<input type="text" name="' . $name . '"  value="' . $value . '" id="' . $name . '" class="form-control" readonly="">';
        $input_html .= '</div>';
        $input_html .= '</div>';
        return $input_html;
    }

    static function getInputFile($name, $value, $title) {
        $input_id = md5($name . time() . rand(0, 99));
        $input_html = '<div class="form-group">';
        $input_html .= '<label class="col-sm-2 control-label" for="' . $name . '">' . $title . '</label>';
        $input_html .= '<div class="col-sm-10">';
        $input_html .= '<a title="Upload file" href="' . config('manager.filemanager.url') . "/dialog.php?type=2&field_id=" . $input_id . "&akey=" . config("manager.filemanager.key") . '" class="iframe-btn"><input type="text" name="' . $name . '"  value="' . $value . '" id="' . $input_id . '" class="form-control" readonly=""></a>';
        $input_html .= '</div>';
        $input_html .= '</div>';
        return $input_html;
    }

    static function getInputEmail($name = '', $value = '', $title = '', $placeholder = '', $required = 'required') {
        $input_html = '<div class="form-group">';
        $input_html .= '<label class="col-sm-2 control-label" for="' . $name . '">' . $title . '</label>';
        $input_html .= '<div class="col-sm-10">';
        $input_html .= '<input ' . $required . ' type="email" name="' . $name . '"  value="' . $value . '" placeholder="' . $placeholder . '" id="' . $name . '" class="form-control">';
        $input_html .= '</div>';
        $input_html .= '</div>';
        return $input_html;
    }

    static function getInputTextarea($name = '', $value = '', $title = '', $max_str = 0) {
        $input_html = '<div class="form-group">';
        $input_html .= '<label class="col-sm-2 control-label" for="' . $name . '">' . $title . '</label>';
        $input_html .= '<div class="col-sm-10">';
        $input_html .= '<textarea name="' . $name . '" id="' . $name . '" class="form-control">' . $value . '</textarea>';
        if( $max_str ) {
            $input_html .= '<span class="help-block m-b-none"> <span class="' . $name . '_count " > 0 </span> /' . $max_str . ' characters</span>';
            $input_html .= '<script>jQuery(document).ready(function () {jQuery(".' . $name . '_count").html((jQuery("#' . $name . '").val().length)); });</script>';
            $input_html .= '<script>jQuery("#' . $name . '").keyup(function (event) {CountUpText(jQuery(this), ' . $max_str . ');});</script>';
        }
        $input_html .= '</div>';
        $input_html .= '</div>';
        return $input_html;
    }

    static function getInputTextareaEdit($name = '', $value = '', $title = '') {
        $input_html = '<div class="form-group">';
        $input_html .= '<label class="col-sm-2 control-label" for="' . $name . '">' . $title . '</label>';
        $input_html .= '<div class="col-sm-10">';
        $input_html .= '<textarea name="' . $name . '" id="editer_cms" class="form-control">' . $value . '</textarea>';
        $input_html .= '</div>';
        $input_html .= '</div>';
        return $input_html;
    }

}
