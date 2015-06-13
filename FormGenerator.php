<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Irap\FormGeneration;

class FormGenerator
{
    private $m_fields = array();             # Array of normal label / value fields.
    private $m_hiddenFields = array();       # Map of name/value pairs of hidden input fields
    private $m_display_submit_button = true;   # Flag for if we should display the submit button.
    private $m_method = 'POST';              # The method under which the form should be posted.
    private $m_useTable = true;              # Flag for if we should use a table instead of divs.
    private $m_id = null;                    # The id of the form, if null then no id is set
    private $m_class = null;                 # class of the form, if null then no class is set.
    private $m_labelClass = 'label';         # If using divs instead of table, this is class of label
    private $m_inputClass = 'input';         # If using divs instead of table, this is class of input
    private $m_submitButtonValue = 'Submit'; # Label to be placed on the submit button.
    private $m_action = null;                # The 'action' of the form (where it is posted)
    private $m_confirmationMessage = null;   # if not null, confirm message will display with this msg
    
    public function __construct() {}
    
    
    public function hide_submit_button()
    {
        $this->m_display_submit_button = false;
    }
    
    
    /**
     * Given an array of name/value pairs, this will add all the hidden input fields for them
     * to be inserted into a form.
     * @param pairs - assoc array of name/value pairs to post
     * @return html - the generated html.
     */
    public function add_hidden_input_fields($pairs)
    {
        foreach ($pairs as $name => $value)
        {
            $this->add_hidden_input_field($name, $value);
        }
    }
    
    
    /**
     * Adds a hidden input field to the form.
     * @param name - the name, this is the post name
     * @param value - the value that should be posted.
     * @return void.
     */
    public function add_hidden_input_field($name, $value)
    {
        $this->m_hiddenFields[$name] = $value;
    }
    
         
    /**
     * Generates the html for this form. Call this last after having finished setting all your 
     * form's properties.
     * @param void
     * @return html - the generated form.
     */
    public function generate_html()
    {
        $html = '';
        
        $html .= PHP_EOL;
        
        $containsLabels = false;
        
        foreach ($this->m_fields as $field)
        {
            /* @var $field InputField */
            if ($field->get_label() != null)
            {
                $containsLabels = true;
                break;
            }
        }
        
        $classField  = '';
        $idField     = '';
        $actionField = '';
        
        if ($this->m_class != null)
        {
            $classField = ' class="' . $this->m_class . '" '; 
        }
        
        if ($this->m_id != null)
        {
            $idField = ' id="' . $this->m_id . '" '; 
        }
        
        if ($this->m_action != null)
        {
            $actionField = ' action="' . $this->m_action . '" ';
        }
        
        $onSubmit = '';
        
        if ($this->m_confirmationMessage != null)
        {
            $onSubmit = ' onsubmit="return confirm(\'' . $this->m_confirmationMessage . '\');" ';
        }
        
        $html .= '<form ' . 
                    'method="' . $this->m_method . '"' . 
                    $classField . 
                    $idField .
                    $actionField .
                    $onSubmit .
                '>';
        
        foreach ($this->m_hiddenFields as $name => $value)
        {
            $html .= "<input type='hidden' name='" . $name . "' value='" . $value . "' />";
        }
        
        
        if ($this->m_useTable)
        {
            $html .= PHP_EOL . '<table>';
            
            foreach ($this->m_fields as $inputField)
            {
                $html .= '<tr>';
                
                if ($containsLabels)
                {
                    $html .= '<td>' . $inputField->get_label() . '</td>';
                }
                       
                $html .=
                        '<td>' . $inputField->generate_html() . '</td>' .
                    '</tr>';
            }
            
            $colspan = 1;
            
            if ($containsLabels)
            {
                $colspan = 2;
            }
            
            $html .= 
                '<tr>' .
                    '<td colspan="' . $colspan . '">' .
                        $this->generate_submit_button() .
                    '</td>' .
                '</tr>';
            
            $html .= '</table>' . PHP_EOL;
        }
        else
        {            
            foreach ($this->m_fields as $label => $inputField)
            {
                $html .= 
                    '<label ' .
                        'class="' . $this->m_labelClass . '" ' .
                        'for="' . $inputField->get_name() . ' ' .
                    '">' . 
                        $inputField->get_label() . 
                    '</label>' .
                    '<div class="' . $this->m_inputClass . '">' . 
                           $inputField->generate_html() . 
                    '</div>';
            }
            
            $html .= $this->generate_submit_button();
        }
        
  
        $html .= '</form>' . PHP_EOL;
         
        return $html;
    }
    
    
    /**
     * Sets the text that should be shown on the submit button. 
     * @param label - the text taht should be shown on the submit button.
     * @return void
     */
    public function set_submit_button_label($label)
    {
        $this->m_submitButtonValue = $label;
    }
    
    
    /**
     * Sets this form method to use GET instead of the POST default
     * @param void
     * @return void
     */
    public function set_method($method)
    {
        $method = strtoupper($method);
        
        if ($method != 'GET' && $method != 'POST')
        {
            throw new \Exception('FormGenerator::setMethod needs to be GET or POST');
        }
        
        $this->m_method = $method;
    }
    
    
    public function add_input_field(InputField $inputField)
    {
        $this->m_fields[$inputField->get_name()] = $inputField;
    }
    
    
    /**
     * Essentially this does exactly the same thing as addInputField, but this is easier to
     * read/understand as a programmer. If you read two 'adds' you would think there were
     * two fields with the same name in the form.
     * @param inputField - the input field being added/replacing the original one.
     * @return 
     */
    public function update_input_field(InputField $inputField)
    {
        $this->m_fields[$inputField->get_name()] = $inputField;
    }
    
        
    /**
     * Generates a submit button for a form.
     * 
     * @param label - The text that will be displayed over the button
     * @param offscreen - render the submit button offscreen so that it does not appear within the
     *                    form, but allows the form to be submitted by hitting enter. Setting
     *                    display:none would work in FF but not chrome
     * 
     * @return html - The html code for the button
     */
    private function generate_submit_button()
    {
        $styleAttribute = '';
        
        if (!$this->m_display_submit_button)
        {
            $styleAttribute = ' style="position: absolute; left: -9999px" ';
        }

        $html = '<input ' .
                    'type="submit" ' .
                    'value="' . $this->m_submitButtonValue . '" ' .
                     $styleAttribute . 
                '/>'; 
        
        return $html;
    }
    
    
    # Set methods
    public function set_action($action) { $this->m_action = $action; }
    public function set_id($newId) { $this->m_id = $newId; }
    public function set_class($newClass) { $this->m_class = $newClass; }
    public function set_use_table($input) { $this->m_useTable = $input; }
    public function set_confirmation_message($message) { $this->m_confirmationMessage = $message; }
    
    # Accessor functions.
    public function get_input_field($fieldName) { return $this->m_fields[$fieldName]; }
}
