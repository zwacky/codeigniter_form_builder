CodeIgniter Form Builder
========================

CodeIgniter library to build uniform form elements with bootstrap styling. It makes building form elements faster and easier.
It's structured in 4 sections:
* Initialization
* Elements
* Utility
* Multi Elements


Initialization
==============
put form_builder.php into your `application/library` directory and you're ready to go. calling functions will output the html right away. best to be used inside templates.

load inside controller:

    $this->load->library('form_builder');
    
use inside template:

    $this->form_builder->text('id_something', 'ID');

the functions hold default parameters that repeat themselves. they are the following:
* **default**: the default value
* **class**: additional css class(es)
* **placeholder**: the html5 placeholder tag


Elements
========

Text
----

    text($id, $name, $default = '', $class = 'input-large', $placeholder = '', $prepend = '', $append = '')

additional parameters:
* **prepend**: prepended input (before text)
* **append**: appended input (after text)

![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/text.png)

    $this->form_builder->text('per_month', 'per Monat', '1350', 'input-large', 'Betrag', '', 'CHF');
    $this->form_builder->text('per_month', 'per Woche', '', 'input-large', 'Betrag', '', 'CHF');

Password
--------

    password($id, $name, $default = '', $class = 'input-large', $placeholder = '', $prepend = '', $append = '') 

additional parameters:
* **prepend**: prepended input (before text)
* **append**: appended input (after text)

![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/text.png)

    $this->form_builder->password('password', 'Passwort', '12345');

Radio
-----

    radio($id, $name, $values, $default = '')
    
The passed object with the multiple properties for the radio elements must contain `id` and `name`. The passed default value will be selected according to the `id` value.
    
![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/radio.png)

    $genders = array(
      (object) array('id' => 1, 'name' => 'Männlich'),
      (object) array('id' => 2, 'name' => 'Weiblich'),
    );
    $this->form_builder->radio('gender', 'Geschlecht', $genders, 2);

Option
------

    option($id, $name, $values, $default = '', $class = 'input-large')

![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/option.png)

    $countries = array(
      (object) array('id' => 1, 'name' => 'Schweiz'),
      (object) array('id' => 2, 'name' => 'Deutschland'),
      (object) array('id' => 3, 'name' => 'Österreich'),
    );
    $this->form_builder->radio('country', 'Land', $countries, 1);

Date
----

    date($id, $name, $default = '', $class = 'input-large', $placeholder = 'TT.MM.JJJJ')

This element requires the javascript library of bootstrap.
* jQuery implementation: https://github.com/eternicode/bootstrap-datepicker

![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/date.png)

    $this->form_builder->date('birthdate', 'Geburtstag');
    
Textarea
--------

    textarea($id, $name, $default = '', $class = 'input-xlarge', $rows = 5)

additional parameters:
* **rows**: amount of rows

![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/textarea.png)

    $this->form_builder->textarea('payment_remark', 'Bemerkung', 'default');
    
Checkbox
--------

    checkbox($name, $id, $value = '', $default = '', $class = '')
    
![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/checkbox.png)

    checkbox('Inaktiv', 'inactive', '', 1);
    checkbox('Eigenschaft', 'settings', 'Inaktiv', 0);

Checkboxes
----------

    checkboxes($name, $id, $boxes, $default = '', $class = '')

The passed object with the multiple properties for the radio elements must contain `id` and `name`. The passed default value will be selected according to the `id` value.
The checked entries are determined by a string separated by a `,`.

![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/checkboxes.png)

    $equipments = array(
      (object) array('id' => 1, 'name' => 'TV'),
      (object) array('id' => 2, 'name' => 'Mikrowelle'),
      (object) array('id' => 3, 'name' => 'Kühlschrank'),
    );
    $this->form_builder->checkboxes('Ausstattung', 'equipment', $equipments, '1,3');

Single Button (submit)
----------------------
    
    single_button($name, $id = '', $class = '', $icon = '')

additional parameters:
* **icon**: the bootstrap icon class (http://twitter.github.com/bootstrap/base-css.html#icons)

![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/single_button.png)

    $this->form_builder->single_button('speichern', 'submit_id', 'btn-primary');

Button
------

    button($id, $name, $class = '')
    
![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/button.png)

    $this->form_builder->button('apply', 'übernehmen', 'disabled');

Hidden
------

    hidden($id, $default = '')

    $this->form_builder->button('contact_id', '55');

translates into:

    <input type="hidden" id="contact_id" value="55" />


Utility
=======

Separation
----------

    add_separation();
    
This will add a little gap between two following elements.
As seen in the example code, the add_separation must be called one element before the actual break happens.

![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/add_separation.png)

    $this->form_builder->text('monthly', 'per Monat', 0, 'span6', '', '', 'CHF');
    $this->form_builder->text('weekly', 'per Woche', 0, 'span6', '', '', 'CHF');
    $this->form_builder->add_separation();
    $this->form_builder->text('daily', 'per Tag', 0, 'span6', '', '', 'CHF');
    $this->form_builder->text('additional_cost', 'Sonstiges', 0, 'span6', '', '', 'CHF');
    
Editable
--------

  set_editable($value);
  
additional parameters:
* **value**: boolean

This makes the following form elements editable or non-editable.


Multi Form Elements
===================

This is rather powerful, that's why it has its own section.
Basically it combines all the before listed parameters and put them together. You can have as many form elements in a row as you like, you just have to keep the array parameters appropriately.

    multi($ids, $name, $types, $default = array(), $class = array(), $param = array(), $prepend = array(), $append = array())
    
![ScreenShot](https://github.com/zwacky/codeigniter_form_builder/raw/master/readme-data/multi.png)

    $this->form_builder->multi(
      array('phone_area', 'phone'), 
      'Vorwahl / Telefon', 
      array(Form_builder::$TYPES->TEXT, Form_builder::$TYPES->TEXT), 
      array('555', '123456'), 
      array('span3', 'span7')
    );
    
Types
-----

    Form_builder::$TYPES = (object) array(
    	'TEXT' => 1,
			'OPTION' => 2,
			'CHECKBOX' => 3,
			'DATE' => 4,
			'RADIO' => 5,
      'BUTTON' => 6,
			'PASSSWORD' => 7,
		);