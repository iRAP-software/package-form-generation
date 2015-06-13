<?php

/*
 * Checkbox input field.
 * Remember than the value in this case is what will be sent if the checkbox is checked, not whether
 * or not it is checked.
 */

namespace Irap\FormGeneration;

class Checkbox extends InputField
{    
    protected $m_checked = false;
    
    
    /**
     * Create a checkbox input field.
     * @param string $name - the name of the name/value pair to send off if checked
     * @param mixed $value - the value to send off if checked
     * @param bool $checked - optional flag indicating if should be marked as checked
     */
    protected function __construct($name, $value, $checked=false) 
    {
        $this->m_checked = $checked;
        parent::__construct('checkbox', $name, $value);
    }
    
    
    protected function generate_middle()
    {
        $middle = parent::generate_middle();
        
        if ($this->m_checked)
        {
            $middle .= ' checked=checked ';
        }
        
        $middle .= '>';
        
        return $middle;
    }
    
    
    /**
     * Overriding the set_placeholder function to throw an error because checkboxes cant have placeholders.
     * @param string $placeholder
     * @throws \Exception
     */
    public function set_placeholder($placeholder)
    {
        throw new \Exception('Checkboxes cant have placeholders! Try using set_label()');
    }
    
    
    protected function generate_closing()
    {
        $closing = '';
        
        if (!empty($this->m_label) )
        {
            $closing = $this->m_label;
        }
        
        return $closing;
    }
}
?>
