<?php
require_once ("secure_area.php");
class Config extends Secure_area 
{
	function __construct()
	{
		parent::__construct('config');
	}
	
	function index()
	{
		$location_names = array();
		$stock_locations = $this->Stock_locations->get_all()->result_array();
		$this->load->view("config", array('stock_locations' => $stock_locations));
	}
		
	function save()
	{
		$barcode_labels = preg_replace('/^_|barcode_label_|_$/', '', implode('_', array(
				$this->input->post('barcode_label_name'), 
				$this->input->post('barcode_label_price'), 
				$this->input->post('barcode_label_company')
		)));
		$batch_save_data=array(
		'company'=>$this->input->post('company'),
		'address'=>$this->input->post('address'),
		'phone'=>$this->input->post('phone'),
		'email'=>$this->input->post('email'),
		'fax'=>$this->input->post('fax'),
		'website'=>$this->input->post('website'),
		'default_tax_1_rate'=>$this->input->post('default_tax_1_rate'),		
		'default_tax_1_name'=>$this->input->post('default_tax_1_name'),		
		'default_tax_2_rate'=>$this->input->post('default_tax_2_rate'),	
		'default_tax_2_name'=>$this->input->post('default_tax_2_name'),		
		'currency_symbol'=>$this->input->post('currency_symbol'),
		'currency_side'=>$this->input->post('currency_side'),/**GARRISON ADDED 4/20/2013**/
		'return_policy'=>$this->input->post('return_policy'),
		'language'=>$this->input->post('language'),
		'timezone'=>$this->input->post('timezone'),
		'print_after_sale'=>$this->input->post('print_after_sale'),
        'tax_included'=>$this->input->post('tax_included'),
		'recv_invoice_format'=>$this->input->post('recv_invoice_format'),
		'sales_invoice_format'=>$this->input->post('sales_invoice_format'),
		'barcode_labels'=>$barcode_labels,
		'barcode_content'=>$this->input->post('barcode_content'),
		'receiving_calculate_average_price'=>$this->input->post('receiving_calculate_average_price'),
		'thousands_separator'=>$this->input->post('thousands_separator'),
		'decimal_point'=>$this->input->post('decimal_point'),
		'custom1_name'=>$this->input->post('custom1_name'),/**GARRISON ADDED 4/20/2013**/
		'custom2_name'=>$this->input->post('custom2_name'),/**GARRISON ADDED 4/20/2013**/
		'custom3_name'=>$this->input->post('custom3_name'),/**GARRISON ADDED 4/20/2013**/
		'custom4_name'=>$this->input->post('custom4_name'),/**GARRISON ADDED 4/20/2013**/
		'custom5_name'=>$this->input->post('custom5_name'),/**GARRISON ADDED 4/20/2013**/
		'custom6_name'=>$this->input->post('custom6_name'),/**GARRISON ADDED 4/20/2013**/
		'custom7_name'=>$this->input->post('custom7_name'),/**GARRISON ADDED 4/20/2013**/
		'custom8_name'=>$this->input->post('custom8_name'),/**GARRISON ADDED 4/20/2013**/
		'custom9_name'=>$this->input->post('custom9_name'),/**GARRISON ADDED 4/20/2013**/
		'custom10_name'=>$this->input->post('custom10_name')/**GARRISON ADDED 4/20/2013**/
		);
		
		$deleted_locations = $this->Stock_locations->get_allowed_locations();
		foreach($this->input->post() as $key => $value) 
		{
        	if (strstr($key, 'stock_location'))
        	{
      			$location_id = preg_replace("/.*?_(\d+)$/", "$1", $key);
      			unset($deleted_locations[$location_id]);
        		// save or update
      			$location_data = array('location_name' => $value);
        		if ($this->Stock_locations->save($location_data, $location_id))
        		{
        			$this->_clear_session_state();
        		}
        	}
		}
        // all locations not available in post will be deleted now
        foreach ($deleted_locations as $location_id => $location_name)
        {
        	$this->Stock_locations->delete($location_id);
        }
        
		if( $this->Appconfig->batch_save( $batch_save_data ))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('config_saved_successfully')));
		}
		$this->_remove_duplicate_cookies();	
	}
	
	function stock_locations() 
	{
		$stock_locations = $this->Stock_locations->get_all()->result_array();
		$this->load->view('partial/stock_locations', array('stock_locations' => $stock_locations));
	} 
	
	function _clear_session_state()
	{
		$this->load->library('sale_lib');
		$this->sale_lib->clear_sale_location();
		$this->sale_lib->clear_all();
		$this->load->library('receiving_lib');
		$this->receiving_lib->clear_stock_source();
		$this->receiving_lib->clear_stock_destination();
		$this->receiving_lib->clear_all();
	}
}
?>