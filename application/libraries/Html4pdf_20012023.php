<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Html4pdf {

    var $html;
    var $path;
    var $filename;
    var $paper_size;
    var $orientation;
    
    /**
     * Constructor
     *
     * @access	public
     * @param	array	initialization parameters
     */	
    function Html4pdf($params = array())
    {
        $this->CI =& get_instance();
        
        if (count($params) > 0)
        {
            $this->initialize($params);
        }
    	
        log_message('debug', 'PDF Class Initialized');
    
    }

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */	
    function initialize($params)
	{
        $this->clear();
		if (count($params) > 0)
        {
            foreach ($params as $key => $value)
            {
                if (isset($this->$key))
                {
                    $this->$key = $value;
                }
            }
        }
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set html
	 *
	 * @access	public
	 * @return	void
	 */	
	function html($html = NULL)
	{
        $this->html = $html;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set path
	 *
	 * @access	public
	 * @return	void
	 */	
	function folder($path)
	{
        $this->path = $path;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set path
	 *
	 * @access	public
	 * @return	void
	 */	
	function filename($filename)
	{
        $this->filename = $filename;
	}
	
	// --------------------------------------------------------------------


	/**
	 * Set paper
	 *
	 * @access	public
	 * @return	void
	 */	
	function paper($paper_size = NULL, $orientation = NULL)
	{
        $this->paper_size = $paper_size;
        $this->orientation = $orientation;
	}
	
	// --------------------------------------------------------------------


	/**
	 * Create PDF
	 *
	 * @access	public
	 * @return	void
	 */	
	function create($mode = 'download') 
	{
	    
   		if (is_null($this->html)) {
			show_error("HTML is not set");
		}
	    
   		if (is_null($this->path)) {
			show_error("Path is not set");
		}
	    
   		if (is_null($this->paper_size)) {
			show_error("Paper size not set");
		}
		
		if (is_null($this->orientation)) {
			show_error("Orientation not set");
		}
	    
	    //Load the DOMPDF libary
	    require_once("dompdf/dompdf_config.inc.php");
	    
	    $dompdf = new DOMPDF();
	    $dompdf->load_html($this->html);
	    $dompdf->set_paper($this->paper_size, $this->orientation);
	    $dompdf->render();
	    
	    if($mode == 'save') {
    	    $this->CI->load->helper('file');
		    if(write_file($this->path.$this->filename, $dompdf->output())) {
		    	return $this->path.$this->filename;
		    } else {
				show_error("PDF could not be written to the path");
		    }
		} else {
			
			if($dompdf->stream($this->filename)) {
				return TRUE;
			} else {
				show_error("PDF could not be streamed");
			}
	    }
	}
	
	function output($option) 
	{
	    
   		if (is_null($this->html)) {
			show_error("HTML is not set");
		}
	    
   		
   		if (is_null($this->paper_size)) {
			show_error("Paper size not set");
		}
		
		if (is_null($this->orientation)) {
			show_error("Orientation not set");
		}
	    

	    //Load the DOMPDF libary
	    require_once("dompdf/dompdf_config.inc.php");
	    
	    $dompdf = new DOMPDF();
	    $dompdf->load_html($this->html);
	    $dompdf->set_paper($this->paper_size, $this->orientation);
	    $dompdf->render();

		$canvas = $dompdf->get_canvas();
		$footer = $canvas->open_object();
		$w = $canvas->get_width();
		$h = $canvas->get_height();
		$fontBold = Font_Metrics::get_font("helvetica", "italic");
		$canvas->page_text($w-120,$h-28,date("Y-m-d H:i:s"), $fontBold,10);
		$canvas->page_text($w-210,$h-28,"Página {PAGE_NUM} de {PAGE_COUNT}", $fontBold,10);
		if ($this->orientation=='landscape') {
			$canvas->page_text($w-800,$h-28,"SISTEMA DE FACTURACIÓN ELECTRÓNICA TIKVASYST  ", $fontBold,10);
		}else{
			$canvas->page_text($w-550,$h-28,"SISTEMA DE FACTURACIÓN ELECTRÓNICA TIKVASYST  ", $fontBold,10);
		}
		


	    $dompdf->stream($this->filename,$option);
	    
	}
	
}

/* End of file Html2pdf.php */