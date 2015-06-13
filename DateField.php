<?php

/*
 * Very similar to the Inputfield, except that we automatically provide a unique ID and use jquery
 * to turn the field into a datepicker.
 */

namespace Irap\FormGeneration;

class DateField extends InputField
{    
    protected $m_dateFormat = "dd/mm/yy";
    
    protected function __construct($name) 
    {
        $this->m_name = $name;
        $this->m_id = \Irap\CoreLibs\Core::generate_unique_id($prefix='date_field_');
    }
    

    protected function generate_closing()
    {
        $html = parent::generate_closing();
        
        $html .= 
            '<script type="text/javascript">
                $(function(){ 
                    $("#' . $this->m_id . '").datepicker({dateFormat: "' . $this->m_dateFormat . '"});
                 });                    
            </script>';
        
        return $html;
    }
    
    
    /**
     * Allows the user to switch back to american date format if desired.
     */
    public function set_american_format()
    {
        $this->m_dateFormat = "mm/dd/yy";
    }
}
