<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Irap\FormGeneration;

class Dropdown extends InputField
{
    private $m_options = null; # name/value pairs for drop down menu only.
    private $m_multipleSelect = false;
    private $m_numRows = 1;
    
    /**
     * The constructor is only meant to be called from the parent class.
     * @param String $name - the html "name" for the dropdown.
     */
    protected function __construct($name) 
    {
        $this->m_name = $name;
    }
    
    protected function generate_opening()
    {
        $html = '<select ';
        return $html;
    }
    
    
    protected function generate_middle() 
    {
        $html = '';
        
        if ($this->m_multipleSelect)
        {
            $html .= ' multiple ';
        }
        
        $html .= ' rows="' . $this->m_numRows . '" ';
        
        $html .= '>'; # Close off the opening which has all the properties set now.
        
        $isAssoc = \Irap\CoreLibs\ArrayLib::is_assoc($this->m_options);
        
        foreach ($this->m_options as $index => $value)
        {
            if ($isAssoc)
            {
                $label = $index;
            }
            else
            {
                $label = $value;
            }
            
            $selected = '';

            if ($value === $this->m_value)
            {
                $selected = ' selected="true" ';
            }

            $html .= '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
        }
        
        return $html;
    }
    
    protected function generate_closing()
    {
        return '</select>';
    }
    
    
    public function enable_multiple_select() { $this->m_multipleSelect = true; }
    public function set_options($options)    { $this->m_options = $options; }
    
    
    public function set_rows($numRows)
    {
        if (!is_int($numRows))
        {
            throw new \Exception('SetRows expects an integer.');
        }
        
        $this->m_numRows = $numRows;
    }
}
?>
