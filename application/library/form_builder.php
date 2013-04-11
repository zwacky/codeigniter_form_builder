<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * library to build uniform form elements with bootstrap styling.
 * @author sim wicki
 */
class Form_builder {
	
	private $_separation = false;
	private $_editable = true;
	public static $TYPES;
	
	public function __construct() {
		Form_builder::$TYPES = (object) array(
			'TEXT' => 1,
			'OPTION' => 2,
			'CHECKBOX' => 3,
			'DATE' => 4,
			'RADIO' => 5,
			'BUTTON' => 6,
			'PASSSWORD' => 7,
		);
	}
	
	/**
	 * builds and prints out a text input section.
	 * @param id string
	 * @param name string
	 * @param default string
	 * @param class string
	 * @param placeholder string
	 * @param prepend string
	 * @param append string
	 */
	public function text($id, $name, $default = '', $class = 'input-large', $placeholder = '', $prepend = '', $append = '') {
		$this->base_control($id, $name);
		$this->_build_text($id, $default, $class, $placeholder, $prepend, $append);
		$this->base_end();
	}
	
	/**
	* builds and prints out a password input section.
	* @param id string
	* @param name string
	* @param default string
	* @param class string
	* @param placeholder string
	* @param prepend string
	* @param append string
	*/
	public function password($id, $name, $default = '', $class = 'input-large', $placeholder = '', $prepend = '', $append = '') {
		$this->base_control($id, $name);
		$this->_build_text($id, $default, $class, $placeholder, $prepend, $append, true);
		$this->base_end();
	}
	
	
	
	/**
	 * creates a form element with multiple inputs defined by form_builder types.
	 * @param ids array
	 * @param name string
	 * @param default array
	 * @param class array
	 * @param param array used for placeholder or values
	 * @param prepend array
	 * @param append array
	 */
	public function multi($ids, $name, $types, $default = array(), $class = array(), $param = array(), $prepend = array(), $append = array()) {
		
		$this->base_control($ids[0], $name);
		
		for ($i = 0; $i < count($types); $i++) {
			$type = isset($types[$i]) ? $types[$i] : '';
			$id = isset($ids[$i]) ? $ids[$i] : 0;
			$df = isset($default[$i]) ? $default[$i] : '';
			$cl = isset($class[$i]) ? $class[$i] : '';
			$pm = isset($param[$i]) ? $param[$i] : '';
			$pp = isset($prepend[$i]) ? $prepend[$i] : '';
			$ap = isset($append[$i]) ? $append[$i] : '';
			
			switch ($type) {
				case Form_builder::$TYPES->TEXT:
					$this->_build_text($id, $df, $cl, $pm, $pp, $ap, false);
					break;
				case Form_builder::$TYPES->OPTION:
					$this->_build_option($id, $pm, $df, $cl);
					break;
				case Form_builder::$TYPES->CHECKBOX:
					$this->_build_checkboxes($id, $pm, $df, $cl);
					break;
				case Form_builder::$TYPES->DATE:
					$this->_build_date($id, $df, $cl, $pm);
					break;
				case Form_builder::$TYPES->BUTTON:
					$this->single_button($df, $id, $cl, '', 'button');
					break;
				case Form_builder::$TYPES->PASSWORD:
					$this->_build_text($id, $df, $cl, $pm, $pp, $ap, true);
					break;
			}
		}
		
		$this->base_end();
	}
	
	/**
	 * builds and prints out a radio input section.
	 * @param id string
	 * @param name string
	 * @param values array containing id, name
	 * @param default string
	 */
	public function radio($id, $name, $values, $default = '') {
		$this->base_control($id, $name);
		
		foreach ($values as $value) {
			echo '<label class="radio">';
			echo '<input type="radio" name="'. $id .'" id="'. $id .'" value="'. $value->id .'" '. set_radio($id, $value->id, $default == $value->id) .'>';
			echo $value->name;
			echo '</label>';
		}
		
		$this->base_end();
	}
	
	/**
	 * builds and prints out an option input section.
	 * @param di string
	 * @param name string
	 * @param values array containing id, name
	 * @param default string
	 * @param class string
	 */
	public function option($id, $name, $values, $default = '', $class = 'input-large') {
		$this->base_control($id, $name);
		$this->_build_option($id, $values, $default, $class);
		$this->base_end();
	}
	
	/**
	 * builds and prints out a date input section.
	 * @param id string
	 * @param name string
	 * @param default string
	 * @param class string
	 * @param placeholder string
	 */
	public function date($id, $name, $default = '', $class = 'input-large', $placeholder = 'TT.MM.JJJJ') {
		$this->base_control($id, $name);
		$this->_build_date($id, $default, $class, $placeholder);
		$this->base_end();
	}
	
	/**
	 * builds and prints out a textarea input section.
	 * @param id string
	 * @param name string
	 * @param default string
	 * @param class string
	 * @param rows int
	 */
	public function textarea($id, $name, $default = '', $class = 'input-xlarge', $rows = 5) {
		$this->base_control($id, $name);
		
		$readonly = ($this->_editable) ? '' : 'readonly';
		echo '<textarea class="'. $class .'" '. $readonly .' id="'. $id .'" name="'. $id .'" rows="'. $rows .'">'. set_value($id, $default) .'</textarea>';
		
		$this->base_end();
	}
	
	/**
	* builds and prints out checkboxes input section.
	* @param name string
	* @param id string
	* @param boxes array
	* @param default string concatenated string with ids, separated by ','
	* @param class string
	* @param disabled boolean
	*/
	public function checkboxes($name, $id, $boxes, $default = '', $class = '') {
		$this->base_control('', $name);
		$this->_build_checkboxes($id, $boxes, $default, $class);
		$this->base_end();
	}
	
	/**
	 * builds and prints out a single checkbox input section.
	 * @param name string
	 * @param id string
	 * @param default string
	 * @param class string
	 */
	public function checkbox($name, $id, $value = '', $default = '', $class = '') {
		$this->base_control('', $name);
		$boxes = array(
			(object) array('id' => $id, 'name' => $value),
		);
		if ($default == '1') {
			$default = $id;
		}
		$this->_build_checkboxes($id, $boxes, $default, $class);
		$this->base_end();
	}
	
	/**
	 * builds and prints a single button without indentation.
	 * @param id string
	 * @param name string
	 * @param class string
	 * @param icon string
	 * @param type string [button|submit|reset]
	 */
	public function single_button($name, $id = '', $class = '', $icon = '', $type = 'submit') {
		$this->_build_button($id, $name, $class, $icon, $type);
	}
	
	/**
	 * builds and prints a single button with the proper indentation.
	 * @param id string
	 * @param name string
	 * @param class string
	 */
	public function button($id, $name, $class = '', $icon = '', $type = 'button') {
		$this->base_control('', '');
		$this->_build_button($id, $name, $class, $icon, $type);
		$this->base_end();
	}
	
	
	
	/**
	 * builds and prints a hidden input field.
	 * @param id string
	 * @param default string
	 */
	public function hidden($id, $default = '') {
		echo '<input type="hidden" id="'. $id .'" name="'. $id .'" value="'. $default . '" />';
	}
	
	/**
	 * builds and prints a single label on the left side (correctly indented) with variable content on the right side.
	 * @param name string
	 * @param content string
	 */
	public function label($name, $content) {
		$this->base_control('', $name);
		echo "<div style='margin-top:5px;'>{$content}</div>";
		$this->base_end();
	}
	
	/**
	* adds a small gap for the next form input.
	*/
	public function add_separation() {
		$this->_separation = true;
	}
	
	/**
	 * whether the following form inputs are editable or non-editable.
	 * @param value string
	 */
	public function set_editable($value) {
		$this->_editable = $value;
	}
	
	/**
	 * outputs the beginning of an input section.
	 * @param id string
	 * @param name string
	 */
	private function base_control($id, $name) {
		$error = '';
		$for = '';
		if (is_array($id)) {
			for ($i = 0; $i < count($id); $i++) {
				if ($error == '' && form_error($id[$i])) {
					$error = 'error';
				}
			}
			$for = $id[0];
		} else {
			$error = (form_error($id)) ? 'error' : '';
			$for = $id;
		}
		
		$lesser = 'lesser-inputs';
		if ($this->_separation) {
			$lesser = '';
			$this->_separation = false;
		}
		echo '<div class="control-group '. $lesser .' '. $error . '">';
		echo '	<label class="control-label" for="'. $for .'">'. $name .'</label>';
		echo '	<div class="controls">';
	}
	
	
	/* ============================================
	 * private functions for building the elements.
	 * ============================================ */
	
	private function _build_text($id, $default, $class, $placeholder, $prepend, $append, $is_password = false) {
		$this->addon_begin($prepend, $append, $class);
		$readonly = ($this->_editable) ? '' : 'readonly';
		$type = ($is_password) ? 'password' : 'text';
		$value = ($is_password) ? '' : set_value($id, $default);
		echo '<input type="'. $type .'" '. $readonly .' placeholder="'. $placeholder .'" class="'. $class . '" name="'. $id .'" id="'. $id .'" value="'. $value .'" /> ';
		$this->addon_end($prepend, $append);
		
	}
	
	private function _build_option($id, $values, $default, $class) {
		$disabled = ($this->_editable) ? '' : 'disabled';
		echo '<select '. $disabled .' name="'. $id .'" id="'. $id .'" class="'. $class .'">';
		echo '	<option value=""></option>';
		foreach ($values as $value) {
			echo '<option value="'. $value->id .'" '. set_select($id, $value->id, $default == $value->id) .'>'. $value->name .'</option>';
		}
		echo '</select> ';
	}
	
	private function _build_checkboxes($id, $boxes, $default, $class) {
		$disabled = ($this->_editable) ? '' : 'disabled';
		$defaults = explode(',', $default);
		foreach ($boxes as $box) {
			$selected = (in_array($box->id, $defaults)) ? true : false;
			echo '<label class="checkbox '. $class .'">';
			echo '	<input type="checkbox" '. $disabled .' id="'. ((count($boxes) > 1) ? "{$id}_" : '') . $box->id .'" '. ($disabled ? 'disabled' : '') .' name="'. $id . ((count($boxes) > 1) ? '[]' : '') .'" value="'. $box->id .'" '. set_checkbox($id . '[]', $box->id, $selected) .'/>' . $box->name;
			echo '</label> ';
		}
	}
	
	private function _build_date($id, $default, $class, $placeholder) {
		$readonly = ($this->_editable) ? '' : 'readonly';
		$datepicker = ($this->_editable) ? 'data-datepicker="datepicker"' : '';
		echo '<input type="text" '. $readonly .' class="'. $class .'" '. $datepicker .' placeholder="'. $placeholder .'" id="'. $id .'" name="'. $id .'" value="'. set_value($id, $default) .'">';
	}
	
	private function _build_button($id, $name, $class, $icon, $type = 'submit') {
		$disabled = ($this->_editable) ? '' : 'disabled';
		$icon = ($icon != '') ? "<i class='{$icon}'></i> " : '';
		echo "<button type='{$type}' id='{$id}' name='{$id}' {$disabled} class='btn {$class} {$disabled}'>{$icon}{$name}</button>";
	}
	
	
	/* ======================================
	* functions for building the core inputs.
	* ======================================= */
	
	/**
	 * outputs the end of an input section.
	 */
	private function base_end() {
		echo '	</div>';
		echo '</div>';
	}
	
	/**
	* handles the begin of the append or prepend addon.
	* @param prepend string
	* @param append string
	* @param class string
	*/
	private function addon_begin($prepend, $append, $class) {
		if ($append != '' || $prepend != '') {
			if ($prepend != '') {
				echo '<div class="input-prepend '. $class .'">';
				echo '	<span class="add-on">'. $prepend .'</span>';
			} else {
				echo '<div class="input-append '. $class .'">';
			}
		}
	}
	
	/**
	 * handles the end of the append or prepend addon.
	 * @param prepend string
	 * @param append string
	 */
	private function addon_end($prepend, $append) {
		if ($append != '' || $prepend != '') {
			if ($append != '') {
				echo '	<span class="add-on">'. $append .'</span>';
			}
			echo '</div> ';
		}
	}
	
}

/* End of file form_builder.php */
/* Location: ./application/models/form_builder.php */
