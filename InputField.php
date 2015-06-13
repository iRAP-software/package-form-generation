<?php

/*
 * Input field class for the form generator. An input field is something like a 
 * textarea, text field, or a submit button.
 */


namespace Irap\FormGeneration;

class InputField
{
    protected $m_label = null; # display text to show beside the input field
    protected $m_type;
    protected $m_name;
    protected $m_id = null;
    protected $m_class = null;
    protected $m_placeholder = null;
    protected $m_value = null;
    protected $m_read_only = false;
    protected $m_disabled = false;
    protected $m_max_length = null;
    protected $m_required = false; # Remember that setting required does not work in safari (yet)
    protected $m_custom_property_text = ''; # Better if this is not used at all.
    protected $m_title = null;
    protected $m_set_title_to_label = true; # If set to true, then if a title is not set we use label.
    
    
    protected function __construct($type, $name, $value="")
    {
        $this->m_type = $type;
        $this->m_name = $name;
        
        if ($value !== "")
        {
            $this->m_value = $value;
        }
    }
    
    
    public static function create_text($name, $value="")
    {
        return new InputField('text', $name, $value);
    }
    
    public static function create_password($name, $value="")
    {
        return new InputField('password', $name, $value);
    }
    
    public static function create_submit($name, $value="")
    {
        return new InputField('submit', $name, $value);
    }
    
    public static function create_textarea($name, $value="")
    {
        return new Textarea($name, $value);
    }
    
    
    /**
     * 
     * @param type $name
     * @param type $options
     * @return Dropdown - child class that inherits from InputField.
     */
    public static function create_dropdown($name, $options=array())
    {
        $dropdown = new Dropdown($name);
        $dropdown->set_options($options);
        return $dropdown;
    }
    
    
    /**
     * Creates a checkbox/tickbox for the form
     * @param String $name - the name of the field
     * @param mixed $value - the value that should be sent if the checkbox is ticked
     * @param bool $checked - whether the checkbox is ticked
     * @return Checkbox - child class that inherits from InputField.
     */
    public static function create_checkbox($name, $value, $checked=false)
    {
        $checkbox = new Checkbox($name, $value, $checked);
        return $checkbox;
    }
    
    
    /**
     * Create a hidden input field for name/value pairs that are submitted with the form but the
     * user never needs to see. Beware of hackers manipulating these values!
     * @param String $name - the name of the name/value pair
     * @param  mixed $value - the value of the name/value pair
     * @return InputField
     */
    public static function create_hidden($name, $value="")
    {
        return new InputField('hidden', $name, $value);
    }
    
    
    /**
     * Generate a field that allows a user to easily input a date.
     * WARNING - this requires the datepicker UI to be set up see 
     * HtmlGenerator::generateJqueryInclude and HtmlGenerator::generateJqueryUiInclude()
     * @param String $name
     * @return DateField - special class that inherits from inputfield.
     */
    public static function create_date($name)
    {
        return new DateField($name);
    }
    
    
    /**
     * Generates the html for this input field.
     */
    public function generate_html()
    {
        $nameProperty = ' name="' . $this->m_name . '" ';
        
        # I prefer using variables instead of an array since the IDE will spot typos in var names
        # rether than in indexes.
        $idProperty             = '';
        $classProperty          = '';
        $readOnlyProperty       = '';
        $requiredProperty       = '';
        $maxLengthProperty      = '';
        $placeholderProperty    = '';
        $disabledProperty       = '';
        $titleProperty          = '';
        
        if ($this->m_placeholder != null)
        {
            $placeholderProperty .= ' placeholder="' . $this->m_placeholder . '" ';
        }
        
        if ($this->m_id != null)
        {
            $idProperty = ' id="' . $this->m_id . '" ';
        }
        
        if ($this->m_class != null)
        {
            $classProperty = ' class="' . $this->m_id . '" ';
        }
        
        if ($this->m_read_only)
        {
            $readOnlyProperty = ' readonly ';
        }
        
        if ($this->m_disabled)
        {
            $disabledProperty = ' disabled ';
        }
        
        if ($this->m_required)
        {
            $requiredProperty = ' required ';
        }
        
        if ($this->m_title != null)
        {
            $titleProperty = ' title="' . $this->m_title . '" ';
        }
        else
        {
            if ($this->m_set_title_to_label && $this->m_label != null)
            {
                $titleProperty = ' title="' . $this->m_label . '" ';
            }
        }
        
        if ($this->m_max_length != null)
        {
            $maxLengthProperty = ' maxlength="' . $this->m_max_length . '" ';
        }
        
        $html = 
            $this->generate_opening() .
            # Shared properties are defined here
            $nameProperty .
            $idProperty .
            $placeholderProperty .
            $readOnlyProperty .
            $disabledProperty .
            $requiredProperty .
            $maxLengthProperty .
            $titleProperty .
            $this->m_custom_property_text .
             # End of shared properties
            $this->generate_middle() .
            $this->generate_closing();
        
        return $html;
    }
    
    
    /**
     * Generates the 'opening' of the tag with type specific properties. Type has to go here 
     * because the classes that extend this class such as dropdown and textarea will not specify 
     * the type.
     * @param void
     * @return html for the opening of the tag.
     */
    protected function generate_opening()
    {
        return '<input ';
    }
    
    protected function generate_middle()
    {
        $html = ' type="' . $this->m_type . '" ';

        if ($this->m_value != null)
        {
            $html .= ' value="' . $this->m_value . '" ';
        }
        
        return $html;
    }
    
    
    protected function generate_closing()
    {
        return ' />';
    }
    

    
    public function set_id($id)                     { $this->m_id = $id; }
    public function set_class($class)               { $this->m_class = $class; }
    public function set_placeholder($placeholder)   { $this->m_placeholder = $placeholder; }
    public function set_value($value)               { $this->m_value = $value; }
    public function set_label($label)               { $this->m_label = $label; }
    public function set_custom_property_text($text) { $this->m_custom_property_text = $text; }
    public function set_max_length($length)         { $this->m_max_length = intval($length); }
    public function set_title($title)               { $this->m_title = $title; }
    public function set_read_only()                 { $this->m_read_only = true; }
    public function set_disabled()                  { $this->m_disabled = true; }
    public function set_required()                  { $this->m_required = true; }
    
    
    # Accessor functions
    public function get_name()  { return $this->m_name; }
    public function get_label() { return $this->m_label; }
}
