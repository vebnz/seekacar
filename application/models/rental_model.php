<?php
class Rental_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    
    public function populateFormLocations()
    {
        $this->db->distinct();
        $this->db->select('city');
        $this->db->order_by("city", "asc");
        $query = $this->db->get('location');
        return $query->result_array();
    }
	
	public function getCompanies($plocation, $dlocation)
	{		
		$this->db->select('company.name');
		$this->db->select('L1.code AS puc');
		$this->db->select('L2.code AS doc');
		$this->db->from('location AS L1');
		$this->db->join('location AS L2', 'L1.companyid = L2.companyid');
		$this->db->join('company', 'L1.companyid = company.id');
		$this->db->where("L1.city = '{$plocation}' AND L2.city = '{$dlocation}'");
		$query = $this->db->get();
		
        	return $query->result_array();
	}
}
?>
	
