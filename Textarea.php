<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Irap\FormGeneration;

class Textarea extends InputField
{
    protected $m_numRows = null; # Would be better if the dev would use css to size instead.
    protected $m_numCols = null; # Would be better if the dev would use css to size instead.
    
    protected function __construct($name, $value="") 
    {
        $this->m_name = $name;
        
        if (!empty($value))
        {
            $this->m_value = $value;
        }
    }
    
    
    protected function generate_opening()
    {
        $opening = '<textarea ';
        
        if ($this->m_numRows != null)
        {
            $opening .= ' rows="' . $this->m_numRows . '" ';
        }
        
        if ($this->m_numCols != null)
        {
            $opening .= ' columns="' . $this->m_numCols . '" ';
        }
        
        return $opening;
    }
    
    
    protected function generate_middle()
    {
        $html = ' >';
        
        if ($this->m_value != null)
        {
            $html .= $this->m_value;
        }
        
        return $html;
    }
    
    
    protected function generate_closing()
    {
        return '</textarea>';
    }
    
    
    /**
     * Set the number of rows (height in lines) that the textarea should be
     * @param int $rows - the number of columns to set
     * @return void
     */
    public function set_rows($rows)
    {
        $this->m_numRows = $rows;
    }
    
    
    /**
     * Set the number of columns (width in chars) that the textarea should be
     * @param 
     * @return 
     */
    public function set_cols($cols)
    {
        $this->m_numCols = $cols;
    }
}

