<?php

class Rental extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('rental_model');
    }
    
    public function index() {
        $data['locations'] = $this->rental_model->populateFormLocations();
        $data['page_title'] = $this->lang->line('page_title');
        
        $this->form_validation->set_rules('pickuplocation', 'Pick-Up Location', 'required');
        $this->form_validation->set_rules('pickupdate', 'Pick-Up Date', 'required');
        $this->form_validation->set_rules('pickuptime', 'Pick-Up Time', 'required');
        $this->form_validation->set_rules('dropofflocation', 'Drop-Off Location', 'required');
        $this->form_validation->set_rules('dropoffdate', 'Drop-Off Date', 'required');
        $this->form_validation->set_rules('dropofftime', 'Drop-Off Time', 'required');
        
        if ($this->form_validation->run() === FALSE)
        {

            // load values for the view
            $this->load->vars($data);

            // get the view section
            $sections = array(
                'content'       => 'rental/index',
            );  
            $this->template->load('templates/default', $sections);
        }
        else
        {
            //$this->rental_model->search_cars();
            $data['plocation'] = $this->input->post('pickuplocation');
            $data['dlocation'] = $this->input->post('dropofflocation');
            $data['pudate'] = $this->input->post('pickupdate');
            $data['putime'] = $this->input->post('pickuptime');
            $data['dodate'] = $this->input->post('dropoffdate');
            $data['dotime'] = $this->input->post('dropofftime');
            //$data['cars'] = $this->get_cars();            

            // load values for the view
            $this->load->vars($data);

            // get the view section
            $sections = array(
                'content'       => 'rental/results',
            );  
            $this->template->load('templates/default', $sections);
        }        
    }
    
    public function list_companies() {
        $plocation = $this->input->post('pickuplocation');
		$dlocation = $this->input->post('dropofflocation');
		$companies = $this->rental_model->getCompanies($plocation, $dlocation);
        
        echo json_encode($companies);
    }
    
    public function get_cars() {
    
        $name = $this->input->post('name');
		$puc = $this->input->post('puc');
		$doc = $this->input->post('doc');
		
		$pudate = $this->input->post('pickupdate');
		$putime = $this->input->post('pickuptime');
		$dodate = $this->input->post('dropoffdate');
		$dotime = $this->input->post('dropofftime');
		
		$largeCarArray = array();
		
        $locOne = $puc;
        $locTwo = $doc;
                
        switch($name) {
            case 'AceRentals':
            $puDateSplit = explode("/", $pudate);
            $doDateSplit = explode("/", $dodate);
                
            $url = 'http://www.acerentalcars.co.nz/inet/formprocess.php';
            $postdata = array('nextstep' => "2",
                'pickuplocationid' => "$locOne",
                'pdate_day' => "$puDateSplit[0]",
                'pdate_monthyear' => "$puDateSplit[1]/$puDateSplit[2]",
                'pdate' => "$pudate",
                'pickuptime' => '10:00',
                'dropofflocationid' => $locTwo,
                'ddate_day' => "$doDateSplit[0]",
                'ddate_monthyear' => "$doDateSplit[1]/$doDateSplit[2]",
                'ddate' => "$dodate",
                'dropofftime' => '10:00', 
                'driverage' => '18', 
                'promocode' => '');
            
            $data = $this->scrapeSite($url, $postdata);
            $largeCarArray = @$this->AceCars($data);
            break;
        
            case 'Omega':
        		
                $url = 'https://www.omegarentalcars.com/book/QuoteForm';
				$postdata = array('PickupForm' => "$locOne",
					'PickupDate' => "$pudate",
					'PickupTime' => '1000',
					'DropoffTo' => "$locTwo",
					'DropoffDate' => "$dodate",
					'DropoffTime' => '1000',
					'PromoCode' => '',
					'SpecialText' => '',
					'Iframe' => '0',
					'action_SubmitQuoteForm' => '');
                
                $data = $this->scrapeSite($url, $postdata);
                $largeCarArray = @$this->OmegaCars($data);
            break;
            
            case 'Pegasus':
            	
            	$fDateReplaced = urlencode($pudate); 
            	$tDateReplaced = urlencode($dodate); 
            	
            	if ($locOne == $locTwo)
            		$locTwo= '-1';
            	
                $url = 'http://www.rentalcars.co.nz/home/getVehicles?from_location='
                        .$locOne.'&from_date='
                        .$fDateReplaced.'&from_time=1000&to_location='
                        .$locTwo.'&to_date='.
                        $tDateReplaced.'&to_time=1000&surname=&email=&start.x=68&start.y=15';

            	$postdata = array();
            	
            	$data = $this->scrapeSite($url, $postdata);
            	$largeCarArray = @$this->PegasusCars($data);
            
            break;
            
            case 'Thrifty':
            
                $puDateSplit = explode("/", $pudate);
                $doDateSplit = explode("/", $dodate);
                
                $url = 'https://www.thrifty.co.nz/index.cfm/1,1,243,33,html?';
                $postdata = array('PREVIOUSFORM' => 'selectItinerary',
                    'NEXTFORM' => 'selectCar',
                    'FORMID' => '',
                    'CONFNUMBER' => '',
                    'EXISTINGRESERVATION' => '0',
                    'SpecialsPage' => '',
                    'Promotions' => '',
                    'PromotionCodes' => '',
                    'blockoutperiods' => '0',
                    'blockoutstart1' => '',
                    'blockoutend1' => '',
                    'blockoutstart2' => '',
                    'blockoutend2' => '',
                    'blockoutstart3' => '',
                    'blockoutend3' => '',
                    'PICKUP_LOC' => "$locOne",
                    'PICKUP_DAY' => "$puDateSplit[0]",
                    'PICKUP_MONTH' => "$puDateSplit[1]",
                    'PICKUP_YEAR' => "$puDateSplit[2]",  
                    'PICKUP_TIME' => '10',
                    'RETURN_LOC' => "$locTwo",
                    'RETURN_DAY' => "$doDateSplit[0]",
                    'RETURN_MONTH' => "$doDateSplit[1]",
                    'RETURN_YEAR' => "$doDateSplit[2]",
                    'return_time' => '10',
                    'BOTHISLANDS' => '0',
                    'DRIVERSAGE' => '25',
                    'CORPNUMBER_AA' => '',
                    'PO_AA' => '',
                    'PROMOTIONCODE' => '',
                    'PO_PC' => '',
                    'vendor_email' => '', 
                    'BLUECHIPNUMBER' => '',
                    'LASTNAME' => '',
                    'PO_BC' => '',
                    'FREQUENTFLYERPARTNER' => 'None',
                    'FREQUENTFLYERNO' => '',
                    'PO' => '',
                    'AUTOCLUB' => 'Please select...',
                    'WHOLESALENUMBER' => '',
                    'WHOLESALEACCOUNT' => '',
                    'WHOLESALEVOUCHER' => '',
                    'PO_WHOLESALE' => '',
                    'WHOLESALECARCLASS' => '');
                                
                $data = $this->scrapeSite($url, $postdata);
                $largeCarArray = @$this->ThriftyCars($data);
            break;
            
            case 'Britz':
            
                $puDateSplit = explode("/", $pudate);
                $doDateSplit = explode("/", $dodate);
                		
				$url = 'https://secure.britz.co.nz/Selection.aspx';
                $postdata = array('cc' => 'nz',
					'brand' => 'b',
					'ac' => '',
					'sc' => 'ac',
					'vtype' => 'ac',
					'pc' => '',
					'na' => '1',
					'nc' => '',
					'cr' => 'nz',
					'pb' => "$locOne",
					'pd' => "$puDateSplit[0]",
					'pm' => "$puDateSplit[1]",
					'py' => "$puDateSplit[2]",
					'pt' => '10:00',
					'db' => "$locTwo",
					'dd' => "$doDateSplit[0]",
					'dm' => "$doDateSplit[1]",
					'dy' => "$doDateSplit[2]",
					'dt' => '10:00',
					'vh' => '',
					'pv' => '1.0'); 
                    
                $data = $this->scrapeSite($url, $postdata);
                $largeCarArray = @$this->BritzCars($data);
            break;
            
            case 'Budget':
                
                $puDateSplit = explode("/", $pudate);
                $doDateSplit = explode("/", $dodate);
                                  
                $url = 'https://www.budget.co.nz/new_reservation/default.aspx';
                
                $postdata = array('__EVENTTARGET' => 'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$btnGetRate',
                    '__EVENTARGUMENT' => '',
                    '__LASTFOCUS' => '',
                    '__VIEWSTATE' => '/wEPDwULLTIxMjE4ODE1NjcPFgIeEHN0ckFjdGl2ZUNvbnRyb2wFKi9QYWdlQ29udHJvbHMvMjAxMy9SZXNlcnZhdGlvbkJvb2tpbmcuYXNjeBYCZg9kFgwCAQ8WAh4JaW5uZXJodG1sBS1OZXcgUXVvdGVzIC8gUmVzZXJ2YXRpb25zIC0gQnVkZ2V0IFJlbnQgYSBDYXJkAgMPFgIeB2NvbnRlbnRlZAIFDxYCHwJlZAIHDxYCHwIFLU5ldyBRdW90ZXMgLyBSZXNlcnZhdGlvbnMgLSBCdWRnZXQgUmVudCBhIENhcmQCDQ8WAh4HVmlzaWJsZWdkAg8PZBYGAgEPZBYMZg8WAh8BBS1OZXcgUXVvdGVzIC8gUmVzZXJ2YXRpb25zIC0gQnVkZ2V0IFJlbnQgYSBDYXJkAgIPZBYCAgEPFgIeBFRleHQFHFJlbnRpbmcgb3V0c2lkZSBOZXcgWmVhbGFuZD9kAgMPFgIeBWNsYXNzBQZhY3RpdmVkAgQPFgQeBGhyZWYFK2h0dHA6Ly93d3cuYnVkZ2V0LmNvLm56L3RydWNrcy9kZWZhdWx0LmFzcHgeBXRpdGxlBSlUcnVjayAvIFZhbiAvIFV0ZSAvIDRXRCByZW50YWxzIC8gTWluaWJ1c2QCBg8WAh8GBUNodHRwczovL3d3dy5idWRnZXQuY28ubnovcmVzZXJ2YXRpb25zL3ByZXZpb3VzX3JlbnRhbHMvZGVmYXVsdC5hc3B4ZAIJDxYCHgtfIUl0ZW1Db3VudAIDFgZmD2QWAgIBDxYCHwEFD0J1ZGdldCBDYXIgSGlyZWQCAg9kFgICAQ8WBB8BBRtSZXNlcnZhdGlvbnMgJmFtcDsgSW52b2ljZXMfBgUdL25ld19yZXNlcnZhdGlvbi9kZWZhdWx0LmFzcHhkAgQPZBYCAgEPFgQfAQUZTmV3IFF1b3RlcyAvIFJlc2VydmF0aW9ucx8GBR0vbmV3X3Jlc2VydmF0aW9uL2RlZmF1bHQuYXNweGQCAw9kFgICCQ9kFgICAQ8PFgIfA2hkFgwCAQ8WAh8FBQ1vcmFuZ2UgaGVhZGVyZAITDw8WAh4PQ29tbWFuZEFyZ3VtZW50BQVzdGVwMWRkAhUPFgIfBQULZ3JleSBoZWFkZXJkAhkPDxYCHwkFBXN0ZXAyZGQCGw8WAh8FBQtncmV5IGhlYWRlcmQCIQ8PFgIfCQUFc3RlcDNkZAIFDw8WAh4KaXNQb3N0QmFja2dkFgRmDxYCHwNoFgYCAQ8WAh8IAgMWBmYPZBYCAgEPFgQfAQUMVGVybXMgb2YgVXNlHwYFIi90ZXJtc19hbmRfY29uZGl0aW9ucy9kZWZhdWx0LmFzcHhkAgIPZBYCAgEPFgQfAQUOUHJpdmFjeSBQb2xpY3kfBgUVL3ByaXZhY3kvZGVmYXVsdC5hc3B4ZAIED2QWAgIBDxYEHwEFB1NpdGVtYXAfBgUVL3NpdGVtYXAvZGVmYXVsdC5hc3B4ZAICDxYCHwNoZAIFDxYCHwNoZAICDxYCHwNnFgZmD2QWAgIDDxYCHwgCBRYKZg9kFgICAQ9kFgICAQ8WAh8IAgIWBGYPZBYCAgEPZBYCAgEPFgQfBgUkaHR0cDovL3d3dy5idWRnZXQuY28ubnovZGVmYXVsdC5hc3B4HwcFC0NhciBSZW50YWxzFgICAQ8WAh8EBQtDYXIgUmVudGFsc2QCAQ9kFgICAQ9kFgICAQ8WBB8GBStodHRwOi8vd3d3LmJ1ZGdldC5jby5uei90cnVja3MvZGVmYXVsdC5hc3B4HwcFE1RydWNrICYgVmFuIFJlbnRhbHMWAgIBDxYCHwQFE1RydWNrICYgVmFuIFJlbnRhbHNkAgEPZBYCAgEPZBYCAgEPFgIfCAIBFgJmD2QWAgIBD2QWAgIBDxYEHwYFLmh0dHA6Ly93d3cuYnVkZ2V0LmNvLm56L2xvY2F0aW9ucy9kZWZhdWx0LmFzcHgfBwUUQ2FyIFJlbnRhbCBMb2NhdGlvbnMWAgIBDxYCHwQFFENhciBSZW50YWwgTG9jYXRpb25zZAICD2QWAgIBD2QWAgIBDxYCHwgCAhYEZg9kFgICAQ9kFgICAQ8WBB8GBUFodHRwOi8vd3d3LmJ1ZGdldC5jby5uei9zcGVjaWFsX29mZmVycy9zcGVjaWFsX29mZmVyL2RlZmF1bHQuYXNweB8HBQ5TcGVjaWFsIE9mZmVycxYCAgEPFgIfBAUOU3BlY2lhbCBPZmZlcnNkAgEPZBYCAgEPZBYCAgEPFgQfBgU2aHR0cDovL3d3dy5idWRnZXQuY28ubnovYWJvdXRfdXMvcGFydG5lcnMvZGVmYXVsdC5hc3B4HwcFCFBhcnRuZXJzFgICAQ8WAh8EBQhQYXJ0bmVyc2QCAw9kFgICAQ9kFgICAQ8WAh8IAgEWAmYPZBYCAgEPZBYCAgEPFgQfBgUtaHR0cDovL3d3dy5idWRnZXQuY28ubnovYWJvdXRfdXMvZGVmYXVsdC5hc3B4HwcFDEFib3V0IEJ1ZGdldBYCAgEPFgIfBAUMQWJvdXQgQnVkZ2V0ZAIED2QWAgIBDxYCHwUFCGNvbCBsYXN0FgQCAQ8WAh8IAgMWBmYPZBYCAgEPZBYCAgEPFgQfBgUvaHR0cDovL3d3dy5idWRnZXQuY28ubnovY29udGFjdF91cy9kZWZhdWx0LmFzcHgfBwUKQ29udGFjdCBVcxYCAgEPFgIfBAUKQ29udGFjdCBVc2QCAQ9kFgICAQ9kFgICAQ8WBB8GBSxodHRwOi8vd3d3LmJ1ZGdldC5jby5uei9zaXRlbWFwL2RlZmF1bHQuYXNweB8HBQhTaXRlIE1hcBYCAgEPFgIfBAUIU2l0ZSBNYXBkAgIPZBYCAgEPZBYCAgEPFgYfBgUkaHR0cDovL3d3dy5idWRnZXQuY28ubnovZGVmYXVsdC5hc3B4HwcFFEJ1ZGdldCBSZW50IEEgQ2FyIE5aHgZ0YXJnZXQFBl9ibGFuaxYCAgEPFgIfBAUUQnVkZ2V0IFJlbnQgQSBDYXIgTlpkAgMPFgIfA2cWAgIBDxYCHwQFDDA4MDAgMjgzIDQzOGQCAQ8WAh8EBRlCdWRnZXQgUmVudCBBIENhciBMaW1pdGVkZAICD2QWAgIBDxYCHwgCAhYEZg9kFgJmDxUEGW1haWx0bzpyZXNlckBidWRnZXQuY28ubnoFRW1haWwdL2ltYWdlX2xpYnJhcnkvZW1haWwtaWNvbi5naWYFRW1haWxkAgEPZBYCZg8VBC1odHRwczovL3BsdXMuZ29vZ2xlLmNvbS8xMDQxMTY4OTIxMTYxMzU5OTAzODMHR29vZ2xlKx0vaW1hZ2VfbGlicmFyeS9ncGx1cy1pY29uLmdpZgdHb29nbGUrZBgBBR5fX0NvbnRyb2xzUmVxdWlyZVBvc3RCYWNrS2V5X18WBAVtY3RsMDAkY3AxJHBhZ2Vjb250cm9sc18yMDEzX3Jlc2VydmF0aW9uYm9va2luZyRwYWdlY29udHJvbHNfMjAxM19yZXNlcnZhdGlvbnF1aWNrYm9va2luZ256JGNib0xpdmVJbkF1c3RyYWxpYQVkY3RsMDAkY3AxJHBhZ2Vjb250cm9sc18yMDEzX3Jlc2VydmF0aW9uYm9va2luZyRwYWdlY29udHJvbHNfMjAxM19yZXNlcnZhdGlvbnF1aWNrYm9va2luZ256JGNibzI1UGx1cwVyY3RsMDAkY3AxJHBhZ2Vjb250cm9sc18yMDEzX3Jlc2VydmF0aW9uYm9va2luZyRwYWdlY29udHJvbHNfMjAxM19yZXNlcnZhdGlvbnF1aWNrYm9va2luZ256JGNib0FpcnBvcnRMb2NhdGlvbnNPbmx5BXVjdGwwMCRjcDEkcGFnZWNvbnRyb2xzXzIwMTNfcmVzZXJ2YXRpb25ib29raW5nJHBhZ2Vjb250cm9sc18yMDEzX3Jlc2VydmF0aW9ucXVpY2tib29raW5nbnokY2JvUmV0dXJuRGlmZmVyZW50TG9jYXRpb26h+UCejmhS15Q6XfG07IZzqQRNYw==',
                    '__EVENTVALIDATION' => '/wEWwQMCtf7ouw4CsY/h/QIC3Lzx6gcCzeTx+Q4Ch5evsAsC473njAEC/q+avAcCocCIzAsCocCgzAsCosCYzQsCocDEzAsCocCAzAsCocC0zAsCocDczAsCocCUzAsCocD4zAsCocC4zAsCocDMzAsCocDAzAsCoMDEzAsCoMDQzAsCoMCgzAsCoMC4zAsCoMCMzAsCoMCYzQsCoMDwzAsCoMCszAsCoMDAzAsCoMC0zAsCicCMzAsCoMDUzAsCoMD4zAsCoMCozAsCoMCUzAsCoMDczAsCo8CszAsCo8C8zAsCo8C4zAsCo8CMzAsCo8CEzAsCo8CIzAsCo8CAzAsCmMCczQsCo8CgzAsCo8CozAsCo8C0zAsCo8DYzAsCo8CUzAsCo8CkzAsCo8D4zAsClsD4zAsCo8DMzAsCo8CczQsCo8DEzAsCosCczQsCosCkzAsCosD4zAsCosC0zAsCosCszAsCpcCEzAsCpcCUzAsCpcCgzAsCpcDczAsCpcD8zAsCpcD4zAsCpcCMzAsCpcDAzAsCpMC8zAsCpMDYzAsCpMDczAsCpMD4zAsCpMCUzAsCp8C8zAsCp8CszAsCosCMzAsCp8DQzAsCp8DczAsCp8CgzAsCo8DAzAsCp8D4zAsCp8CUzAsCp8CAzAsCp8DwzAsCp8DMzAsCp8DAzAsCp8CozAsCp8CczQsClsDAzAsClsCAzAsClsCkzAsClsDMzAsCmcDEzAsCmcCozAsCmcCAzAsCmcD4zAsCmcD8zAsCmcCMzAsCmcCszAsCmcCgzAsCmcDAzAsCmMCszAsCmMDwzAsCmMC0zAsCm8DQzAsCm8CMzAsCm8DczAsCm8CAzAsCm8DwzAsCm8DUzAsCmsC8zAsCmsC4zAsCmsDEzAsCmsD4zAsCmsCczQsCmsDczAsCmsCMzAsCmsDAzAsCmsDMzAsCncCUzAsCncDUzAsCncCczQsCncDczAsCncCgzAsCncDAzAsCncD4zAsCncDEzAsCncD8zAsCncCszAsCncDMzAsCncCQzQsCncCEzAsCncC0zAsCncCozAsCncC8zAsCncCYzQsCoMDMzAsCk8DUzAsCnMC8zAsCnMCMzAsCocCozAsCnMCgzAsCnMCEzAsCnMDczAsCnMD4zAsCnMCUzAsCnMDEzAsCnMC0zAsCn8CszAsCjsCkzAsC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',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$ddlCarTruck' => 'Car',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$cboLiveInAustralia' => 'on',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$cbo25Plus' => 'on',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$ddlResideInCountry' => '',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$hidDriverAge' => 'To hire a car from Budget you must be 21 years of age or over and hold a full drivers licence.',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$ddlDriverAge' => '',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtPickUpDate' => "$pudate",
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtReturnUpDate' => "$dodate",
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$ddlPickUpTime' => '10:00 AM',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$hidPickupDate' => $puDateSplit[2] . "-" . $puDateSplit[1] . "-" . $puDateSplit[0] . "T10:00:03",
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$ddlReturnTime' => '10:00 AM',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$hidReturnDate' => $doDateSplit[2] . "-" . $doDateSplit[1] . "-" . $doDateSplit[0] . "T10:00:03",
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtPickUpLocationId' => "$locOne",
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$pickupLocationListAll' => ',AKL,AK3,HB6,AK6,AP6,BHE,CHC,DUD,DD1,GIS,Q1A,HLZ,HL1,HKK,IVC,KKE,KK1,NPE,NSN,NPL,NL1,PMR,PO7,ZQN,ZQ1,ROT,RO1,TUO,NZ8,TRG,WAG,WA1,WLG,WL3,WL4,LH8,WHK,WH2,WRE,WR1#Select Location,Auckland - Airport,Auckland - City,Auckland - East Tamaki,Auckland - North Shore,Auckland - Penrose,Blenheim Airport,Christchurch Airport,Dunedin Airport,Dunedin City,Gisborne Airport,Greymouth Railway Station,Hamilton Airport,Hamilton City,Hokitika Airport,Invercargill Airport,Kerikeri Airport,Kerikeri City,Napier Airport,Nelson Airport,New Plymouth Airport,New Plymouth City,Palmerston North Airport,Picton Ferry Terminal,Queenstown Airport,Queenstown City,Rotorua Airport,Rotorua City,Taupo Airport,Taupo City,Tauranga Airport,Wanganui Airport,Wanganui City,Wellington - Airport,Wellington - City,Wellington - Ferry Terminal,Wellington - Lower Hutt,Whakatane Airport,Whakatane City,Whangarei Airport,Whangarei City',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$pickupLocationListAirport' => ',AKL,BHE,CHC,DUD,GIS,HLZ,HKK,IVC,KKE,NPE,NSN,NPL,PMR,PO7,ZQN,ROT,TUO,TRG,WAG,WLG,WL4,WHK,WRE#Select Location,Auckland - Airport,Blenheim Airport,Christchurch Airport,Dunedin Airport,Gisborne Airport,Hamilton Airport,Hokitika Airport,Invercargill Airport,Kerikeri Airport,Napier Airport,Nelson Airport,New Plymouth Airport,Palmerston North Airport,Picton Ferry Terminal,Queenstown Airport,Rotorua Airport,Taupo Airport,Tauranga Airport,Wanganui Airport,Wellington - Airport,Wellington - Ferry Terminal,Whakatane Airport,Whangarei Airport',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$pickupLocation' => "$locOne",
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtDropOffLocationId' => "$locTwo",
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$dropoffLocation' => "$locTwo",
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtFastbreak' => '',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtSurname' => '',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtDirectPIN' => '',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtOTTOACTO' => '',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtBCD' => '',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$ddlProgrammeType' => '',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtMembership' => '',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtVoucherCoupon' => '',
                    'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtIATA' => '');
                
            

                $data = $this->scrapeSite($url, $postdata);								
                $largeCarArray = @$this->BudgetCars($data);
            break;
            
            case 'Jucy':
                // organise post data relevant to company
                // organise date in correct format
                //$data = $this->scrapeSite($url, $postdata);
                //$largeCarArray = $this->JucyCars($data);
            break;
            
            case 'Apex':
                
                $puDateSplit = explode("/", $pudate);
                $doDateSplit = explode("/", $dodate);
                
                $url = 'http://www.apexrentals.co.nz/default.aspx';
                $postdata = array('ctl00$ajaxScripManager' => 'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$upDefaultPageSearch|ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$lnkBtnGO',
'__EVENTTARGET' => 'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$lnkBtnGO',
'__EVENTARGUMENT' => '',
'__LASTFOCUS' => '',
'__VIEWSTATE' => '/wEPDwUKLTU4NjkyODIwNA9kFgJmD2QWAgIDD2QWCAIKDxYCHgVjbGFzcwUMdXRpbGl0eVJpZ2h0FgJmD2QWAmYPZBYEAgEPZBYCAgMPZBYCZg9kFgJmD2QWAgIBD2QWAmYPZBYCAgEPZBYCAgEPZBYCZg9kFgICAQ9kFgICDw8QDxYCHgdDaGVja2VkZ2RkZGQCAw9kFgJmD2QWBAILD2QWAgIDD2QWAmYPZBYCZg9kFgICAw8WAh4HVmlzaWJsZWhkAg8PZBYCAgMPZBYCZg9kFgJmD2QWBGYPZBYQAhYPEGRkFgECAWQCGA8QZGQWAQIBZAIaD2QWAmYPEA8WBB4MQXV0b1Bvc3RCYWNrZx4LXyFEYXRhQm91bmRnZBAVDxUgLSBTZWxlY3QgTG9jYXRpb24gLSAQQXVja2xhbmQgQWlycG9ydA1BdWNrbGFuZCBDaXR5EldlbGxpbmd0b24gQWlycG9ydA9XZWxsaW5ndG9uIENpdHkGUGljdG9uDFBpY3RvbiBGZXJyeRBCbGVuaGVpbSBBaXJwb3J0Dk5lbHNvbiBBaXJwb3J0C05lbHNvbiBDaXR5FENocmlzdGNodXJjaCBBaXJwb3J0EUNocmlzdGNodXJjaCBDaXR5CUdyZXltb3V0aBJRdWVlbnN0b3duIEFpcnBvcnQPUXVlZW5zdG93biBDaXR5FQ8AAzk5MQM5OTADOTk4Azk4NwM5NjgEMTAwMgM5NzQEMTAwMAM5NjkEMTAwMwM5NzADOTg5BDEwMDUDOTgzFCsDD2dnZ2dnZ2dnZ2dnZ2dnZxYBZmQCHw8PFgYeBENvZGULKWVBUEVYLlZlaGljbGVSZXF1ZXN0V3JhcHBlcitlTG9jYXRpb25Db2RlLCBBUEVYLCBWZXJzaW9uPTEuMC4wLjAsIEN1bHR1cmU9bmV1dHJhbCwgUHVibGljS2V5VG9rZW49bnVsbN8HHgtPcGVuaW5nSG91cgIGHgtDbG9zaW5nSG91cgITZBYCZg9kFgJmD2QWCgIFDxBkZBYBAg5kAgcPEGRkFgECCWQCCQ8QZA8WA2YCAQICFgMQBQIxMwUEMjAxM2cQBQIxNAUEMjAxNGcQBQIxNQUEMjAxNWcWAWZkAgsPEGQQFWAKMTI6MDAgYS5tLgoxMjoxNSBhLm0uCjEyOjMwIGEubS4KMTI6NDUgYS5tLgowMTowMCBhLm0uCjAxOjE1IGEubS4KMDE6MzAgYS5tLgowMTo0NSBhLm0uCjAyOjAwIGEubS4KMDI6MTUgYS5tLgowMjozMCBhLm0uCjAyOjQ1IGEubS4KMDM6MDAgYS5tLgowMzoxNSBhLm0uCjAzOjMwIGEubS4KMDM6NDUgYS5tLgowNDowMCBhLm0uCjA0OjE1IGEubS4KMDQ6MzAgYS5tLgowNDo0NSBhLm0uCjA1OjAwIGEubS4KMDU6MTUgYS5tLgowNTozMCBhLm0uCjA1OjQ1IGEubS4KMDY6MDAgYS5tLgowNjoxNSBhLm0uCjA2OjMwIGEubS4KMDY6NDUgYS5tLgowNzowMCBhLm0uCjA3OjE1IGEubS4KMDc6MzAgYS5tLgowNzo0NSBhLm0uCjA4OjAwIGEubS4KMDg6MTUgYS5tLgowODozMCBhLm0uCjA4OjQ1IGEubS4KMDk6MDAgYS5tLgowOToxNSBhLm0uCjA5OjMwIGEubS4KMDk6NDUgYS5tLgoxMDowMCBhLm0uCjEwOjE1IGEubS4KMTA6MzAgYS5tLgoxMDo0NSBhLm0uCjExOjAwIGEubS4KMTE6MTUgYS5tLgoxMTozMCBhLm0uCjExOjQ1IGEubS4KMTI6MDAgcC5tLgoxMjoxNSBwLm0uCjEyOjMwIHAubS4KMTI6NDUgcC5tLgowMTowMCBwLm0uCjAxOjE1IHAubS4KMDE6MzAgcC5tLgowMTo0NSBwLm0uCjAyOjAwIHAubS4KMDI6MTUgcC5tLgowMjozMCBwLm0uCjAyOjQ1IHAubS4KMDM6MDAgcC5tLgowMzoxNSBwLm0uCjAzOjMwIHAubS4KMDM6NDUgcC5tLgowNDowMCBwLm0uCjA0OjE1IHAubS4KMDQ6MzAgcC5tLgowNDo0NSBwLm0uCjA1OjAwIHAubS4KMDU6MTUgcC5tLgowNTozMCBwLm0uCjA1OjQ1IHAubS4KMDY6MDAgcC5tLgowNjoxNSBwLm0uCjA2OjMwIHAubS4KMDY6NDUgcC5tLgowNzowMCBwLm0uCjA3OjE1IHAubS4KMDc6MzAgcC5tLgowNzo0NSBwLm0uCjA4OjAwIHAubS4KMDg6MTUgcC5tLgowODozMCBwLm0uCjA4OjQ1IHAubS4KMDk6MDAgcC5tLgowOToxNSBwLm0uCjA5OjMwIHAubS4KMDk6NDUgcC5tLgoxMDowMCBwLm0uCjEwOjE1IHAubS4KMTA6MzAgcC5tLgoxMDo0NSBwLm0uCjExOjAwIHAubS4KMTE6MTUgcC5tLgoxMTozMCBwLm0uCjExOjQ1IHAubS4VYAUwMDowMAUwMDoxNQUwMDozMAUwMDo0NQUwMTowMAUwMToxNQUwMTozMAUwMTo0NQUwMjowMAUwMjoxNQUwMjozMAUwMjo0NQUwMzowMAUwMzoxNQUwMzozMAUwMzo0NQUwNDowMAUwNDoxNQUwNDozMAUwNDo0NQUwNTowMAUwNToxNQUwNTozMAUwNTo0NQUwNjowMAUwNjoxNQUwNjozMAUwNjo0NQUwNzowMAUwNzoxNQUwNzozMAUwNzo0NQUwODowMAUwODoxNQUwODozMAUwODo0NQUwOTowMAUwOToxNQUwOTozMAUwOTo0NQUxMDowMAUxMDoxNQUxMDozMAUxMDo0NQUxMTowMAUxMToxNQUxMTozMAUxMTo0NQUxMjowMAUxMjoxNQUxMjozMAUxMjo0NQUxMzowMAUxMzoxNQUxMzozMAUxMzo0NQUxNDowMAUxNDoxNQUxNDozMAUxNDo0NQUxNTowMAUxNToxNQUxNTozMAUxNTo0NQUxNjowMAUxNjoxNQUxNjozMAUxNjo0NQUxNzowMAUxNzoxNQUxNzozMAUxNzo0NQUxODowMAUxODoxNQUxODozMAUxODo0NQUxOTowMAUxOToxNQUxOTozMAUxOTo0NQUyMDowMAUyMDoxNQUyMDozMAUyMDo0NQUyMTowMAUyMToxNQUyMTozMAUyMTo0NQUyMjowMAUyMjoxNQUyMjozMAUyMjo0NQUyMzowMAUyMzoxNQUyMzozMAUyMzo0NRQrA2BoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnaGhoaGhoaGhoaGhoaGhoaGhoaGgWAQIkZAIMDxYCHwJoZAIhD2QWAgIBDw8WAh4ISW1hZ2VVcmwFF34vZ3JhcGhpY3Mvbnpfbm9ydGguZ2lmZGQCIw9kFgJmDxAPFgQfA2cfBGdkEBUPFSAtIFNlbGVjdCBMb2NhdGlvbiAtIBBBdWNrbGFuZCBBaXJwb3J0DUF1Y2tsYW5kIENpdHkSV2VsbGluZ3RvbiBBaXJwb3J0D1dlbGxpbmd0b24gQ2l0eQZQaWN0b24MUGljdG9uIEZlcnJ5EEJsZW5oZWltIEFpcnBvcnQOTmVsc29uIEFpcnBvcnQLTmVsc29uIENpdHkUQ2hyaXN0Y2h1cmNoIEFpcnBvcnQRQ2hyaXN0Y2h1cmNoIENpdHkJR3JleW1vdXRoElF1ZWVuc3Rvd24gQWlycG9ydA9RdWVlbnN0b3duIENpdHkVDwADOTkxAzk5MAM5OTgDOTg3Azk2OAQxMDAyAzk3NAQxMDAwAzk2OQQxMDAzAzk3MAM5ODkEMTAwNQM5ODMUKwMPZ2dnZ2dnZ2dnZ2dnZ2dnFgFmZAIoDw8WBh8FCysE3wcfBgIGHwcCE2QWAmYPZBYCZg9kFgoCBQ8QZGQWAQIVZAIHDxBkZBYBAglkAgkPEGQPFgNmAgECAhYDEAUCMTMFBDIwMTNnEAUCMTQFBDIwMTRnEAUCMTUFBDIwMTVnFgFmZAILDxBkEBVgCjEyOjAwIGEubS4KMTI6MTUgYS5tLgoxMjozMCBhLm0uCjEyOjQ1IGEubS4KMDE6MDAgYS5tLgowMToxNSBhLm0uCjAxOjMwIGEubS4KMDE6NDUgYS5tLgowMjowMCBhLm0uCjAyOjE1IGEubS4KMDI6MzAgYS5tLgowMjo0NSBhLm0uCjAzOjAwIGEubS4KMDM6MTUgYS5tLgowMzozMCBhLm0uCjAzOjQ1IGEubS4KMDQ6MDAgYS5tLgowNDoxNSBhLm0uCjA0OjMwIGEubS4KMDQ6NDUgYS5tLgowNTowMCBhLm0uCjA1OjE1IGEubS4KMDU6MzAgYS5tLgowNTo0NSBhLm0uCjA2OjAwIGEubS4KMDY6MTUgYS5tLgowNjozMCBhLm0uCjA2OjQ1IGEubS4KMDc6MDAgYS5tLgowNzoxNSBhLm0uCjA3OjMwIGEubS4KMDc6NDUgYS5tLgowODowMCBhLm0uCjA4OjE1IGEubS4KMDg6MzAgYS5tLgowODo0NSBhLm0uCjA5OjAwIGEubS4KMDk6MTUgYS5tLgowOTozMCBhLm0uCjA5OjQ1IGEubS4KMTA6MDAgYS5tLgoxMDoxNSBhLm0uCjEwOjMwIGEubS4KMTA6NDUgYS5tLgoxMTowMCBhLm0uCjExOjE1IGEubS4KMTE6MzAgYS5tLgoxMTo0NSBhLm0uCjEyOjAwIHAubS4KMTI6MTUgcC5tLgoxMjozMCBwLm0uCjEyOjQ1IHAubS4KMDE6MDAgcC5tLgowMToxNSBwLm0uCjAxOjMwIHAubS4KMDE6NDUgcC5tLgowMjowMCBwLm0uCjAyOjE1IHAubS4KMDI6MzAgcC5tLgowMjo0NSBwLm0uCjAzOjAwIHAubS4KMDM6MTUgcC5tLgowMzozMCBwLm0uCjAzOjQ1IHAubS4KMDQ6MDAgcC5tLgowNDoxNSBwLm0uCjA0OjMwIHAubS4KMDQ6NDUgcC5tLgowNTowMCBwLm0uCjA1OjE1IHAubS4KMDU6MzAgcC5tLgowNTo0NSBwLm0uCjA2OjAwIHAubS4KMDY6MTUgcC5tLgowNjozMCBwLm0uCjA2OjQ1IHAubS4KMDc6MDAgcC5tLgowNzoxNSBwLm0uCjA3OjMwIHAubS4KMDc6NDUgcC5tLgowODowMCBwLm0uCjA4OjE1IHAubS4KMDg6MzAgcC5tLgowODo0NSBwLm0uCjA5OjAwIHAubS4KMDk6MTUgcC5tLgowOTozMCBwLm0uCjA5OjQ1IHAubS4KMTA6MDAgcC5tLgoxMDoxNSBwLm0uCjEwOjMwIHAubS4KMTA6NDUgcC5tLgoxMTowMCBwLm0uCjExOjE1IHAubS4KMTE6MzAgcC5tLgoxMTo0NSBwLm0uFWAFMDA6MDAFMDA6MTUFMDA6MzAFMDA6NDUFMDE6MDAFMDE6MTUFMDE6MzAFMDE6NDUFMDI6MDAFMDI6MTUFMDI6MzAFMDI6NDUFMDM6MDAFMDM6MTUFMDM6MzAFMDM6NDUFMDQ6MDAFMDQ6MTUFMDQ6MzAFMDQ6NDUFMDU6MDAFMDU6MTUFMDU6MzAFMDU6NDUFMDY6MDAFMDY6MTUFMDY6MzAFMDY6NDUFMDc6MDAFMDc6MTUFMDc6MzAFMDc6NDUFMDg6MDAFMDg6MTUFMDg6MzAFMDg6NDUFMDk6MDAFMDk6MTUFMDk6MzAFMDk6NDUFMTA6MDAFMTA6MTUFMTA6MzAFMTA6NDUFMTE6MDAFMTE6MTUFMTE6MzAFMTE6NDUFMTI6MDAFMTI6MTUFMTI6MzAFMTI6NDUFMTM6MDAFMTM6MTUFMTM6MzAFMTM6NDUFMTQ6MDAFMTQ6MTUFMTQ6MzAFMTQ6NDUFMTU6MDAFMTU6MTUFMTU6MzAFMTU6NDUFMTY6MDAFMTY6MTUFMTY6MzAFMTY6NDUFMTc6MDAFMTc6MTUFMTc6MzAFMTc6NDUFMTg6MDAFMTg6MTUFMTg6MzAFMTg6NDUFMTk6MDAFMTk6MTUFMTk6MzAFMTk6NDUFMjA6MDAFMjA6MTUFMjA6MzAFMjA6NDUFMjE6MDAFMjE6MTUFMjE6MzAFMjE6NDUFMjI6MDAFMjI6MTUFMjI6MzAFMjI6NDUFMjM6MDAFMjM6MTUFMjM6MzAFMjM6NDUUKwNgaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2hoaGhoaGhoaGhoaGhoaGhoaGhoFgECJGQCDA8WAh8CaGQCKg8PFgIeBE1vZGULKWtBUEVYLlZlaGljbGVSZXF1ZXN0V3JhcHBlcitlSXNsYW5kU2VsZWN0b3JNb2RlLCBBUEVYLCBWZXJzaW9uPTEuMC4wLjAsIEN1bHR1cmU9bmV1dHJhbCwgUHVibGljS2V5VG9rZW49bnVsbABkFgJmDxBkDxYCZgIBFgIQZGRoEGRkZxYBZmQCAQ8WAh8CaGQCDA9kFggCAQ8PFhAeFWhpZEFmdGVySG91clBpY2t1cEZlZQcAAAAAAAA5QB4caGlkQWZ0ZXJIb3Vyc1BpY2t1cFByaWNlVHlwZWYeGGhpZEFmdGVySG91cnNQaWNrdXBMYWJlbGUeF2hpZEFmdGVySG91cnNQaWNrdXBUZXh0ZR4WaGlkQWZ0ZXJIb3VyRHJvcG9mZkZlZQcAAAAAAAA5QB4daGlkQWZ0ZXJIb3Vyc0Ryb3BvZmZQcmljZVR5cGVmHhloaWRBZnRlckhvdXJzRHJvcG9mZkxhYmVsZR4YaGlkQWZ0ZXJIb3Vyc0Ryb3BvZmZUZXh0ZWQWAmYPZBYCZg9kFhACBw8QDxYCHwRnZBAVBwhhbGwgY2FycwlzbWFsbCBjYXIJbWlkLXNpemVkCWxhcmdlIGNhcg1zdGF0aW9uIHdhZ29uA21wdgM0V0QVBwIxMQEzATgBNAE5ATUCMTAUKwMHZ2dnZ2dnZ2RkAgkPZBYCZg8QDxYEHwNnHwRnZBAVDxUgLSBTZWxlY3QgTG9jYXRpb24gLSAQQXVja2xhbmQgQWlycG9ydA1BdWNrbGFuZCBDaXR5EldlbGxpbmd0b24gQWlycG9ydA9XZWxsaW5ndG9uIENpdHkGUGljdG9uDFBpY3RvbiBGZXJyeRBCbGVuaGVpbSBBaXJwb3J0Dk5lbHNvbiBBaXJwb3J0C05lbHNvbiBDaXR5FENocmlzdGNodXJjaCBBaXJwb3J0EUNocmlzdGNodXJjaCBDaXR5CUdyZXltb3V0aBJRdWVlbnN0b3duIEFpcnBvcnQPUXVlZW5zdG93biBDaXR5FQ8AAzk5MQM5OTADOTk4Azk4NwM5NjgEMTAwMgM5NzQEMTAwMAM5NjkEMTAwMwM5NzADOTg5BDEwMDUDOTgzFCsDD2dnZ2dnZ2dnZ2dnZ2dnZxYBAgFkAgsPZBYCAgEPDxYCHwgFF34vZ3JhcGhpY3Mvbnpfbm9ydGguZ2lmZGQCDQ8PFgYfBQsrBN8HHwYCBh8HAhNkFgJmD2QWAmYPZBYKAgUPEGRkFgECDmQCBw8QZGQWAQIJZAIJDxBkDxYDZgIBAgIWAxAFAjEzBQQyMDEzZxAFAjE0BQQyMDE0ZxAFAjE1BQQyMDE1ZxYBZmQCCw8QZBAVYAoxMjowMCBhLm0uCjEyOjE1IGEubS4KMTI6MzAgYS5tLgoxMjo0NSBhLm0uCjAxOjAwIGEubS4KMDE6MTUgYS5tLgowMTozMCBhLm0uCjAxOjQ1IGEubS4KMDI6MDAgYS5tLgowMjoxNSBhLm0uCjAyOjMwIGEubS4KMDI6NDUgYS5tLgowMzowMCBhLm0uCjAzOjE1IGEubS4KMDM6MzAgYS5tLgowMzo0NSBhLm0uCjA0OjAwIGEubS4KMDQ6MTUgYS5tLgowNDozMCBhLm0uCjA0OjQ1IGEubS4KMDU6MDAgYS5tLgowNToxNSBhLm0uCjA1OjMwIGEubS4KMDU6NDUgYS5tLgowNjowMCBhLm0uCjA2OjE1IGEubS4KMDY6MzAgYS5tLgowNjo0NSBhLm0uCjA3OjAwIGEubS4KMDc6MTUgYS5tLgowNzozMCBhLm0uCjA3OjQ1IGEubS4KMDg6MDAgYS5tLgowODoxNSBhLm0uCjA4OjMwIGEubS4KMDg6NDUgYS5tLgowOTowMCBhLm0uCjA5OjE1IGEubS4KMDk6MzAgYS5tLgowOTo0NSBhLm0uCjEwOjAwIGEubS4KMTA6MTUgYS5tLgoxMDozMCBhLm0uCjEwOjQ1IGEubS4KMTE6MDAgYS5tLgoxMToxNSBhLm0uCjExOjMwIGEubS4KMTE6NDUgYS5tLgoxMjowMCBwLm0uCjEyOjE1IHAubS4KMTI6MzAgcC5tLgoxMjo0NSBwLm0uCjAxOjAwIHAubS4KMDE6MTUgcC5tLgowMTozMCBwLm0uCjAxOjQ1IHAubS4KMDI6MDAgcC5tLgowMjoxNSBwLm0uCjAyOjMwIHAubS4KMDI6NDUgcC5tLgowMzowMCBwLm0uCjAzOjE1IHAubS4KMDM6MzAgcC5tLgowMzo0NSBwLm0uCjA0OjAwIHAubS4KMDQ6MTUgcC5tLgowNDozMCBwLm0uCjA0OjQ1IHAubS4KMDU6MDAgcC5tLgowNToxNSBwLm0uCjA1OjMwIHAubS4KMDU6NDUgcC5tLgowNjowMCBwLm0uCjA2OjE1IHAubS4KMDY6MzAgcC5tLgowNjo0NSBwLm0uCjA3OjAwIHAubS4KMDc6MTUgcC5tLgowNzozMCBwLm0uCjA3OjQ1IHAubS4KMDg6MDAgcC5tLgowODoxNSBwLm0uCjA4OjMwIHAubS4KMDg6NDUgcC5tLgowOTowMCBwLm0uCjA5OjE1IHAubS4KMDk6MzAgcC5tLgowOTo0NSBwLm0uCjEwOjAwIHAubS4KMTA6MTUgcC5tLgoxMDozMCBwLm0uCjEwOjQ1IHAubS4KMTE6MDAgcC5tLgoxMToxNSBwLm0uCjExOjMwIHAubS4KMTE6NDUgcC5tLhVgBTAwOjAwBTAwOjE1BTAwOjMwBTAwOjQ1BTAxOjAwBTAxOjE1BTAxOjMwBTAxOjQ1BTAyOjAwBTAyOjE1BTAyOjMwBTAyOjQ1BTAzOjAwBTAzOjE1BTAzOjMwBTAzOjQ1BTA0OjAwBTA0OjE1BTA0OjMwBTA0OjQ1BTA1OjAwBTA1OjE1BTA1OjMwBTA1OjQ1BTA2OjAwBTA2OjE1BTA2OjMwBTA2OjQ1BTA3OjAwBTA3OjE1BTA3OjMwBTA3OjQ1BTA4OjAwBTA4OjE1BTA4OjMwBTA4OjQ1BTA5OjAwBTA5OjE1BTA5OjMwBTA5OjQ1BTEwOjAwBTEwOjE1BTEwOjMwBTEwOjQ1BTExOjAwBTExOjE1BTExOjMwBTExOjQ1BTEyOjAwBTEyOjE1BTEyOjMwBTEyOjQ1BTEzOjAwBTEzOjE1BTEzOjMwBTEzOjQ1BTE0OjAwBTE0OjE1BTE0OjMwBTE0OjQ1BTE1OjAwBTE1OjE1BTE1OjMwBTE1OjQ1BTE2OjAwBTE2OjE1BTE2OjMwBTE2OjQ1BTE3OjAwBTE3OjE1BTE3OjMwBTE3OjQ1BTE4OjAwBTE4OjE1BTE4OjMwBTE4OjQ1BTE5OjAwBTE5OjE1BTE5OjMwBTE5OjQ1BTIwOjAwBTIwOjE1BTIwOjMwBTIwOjQ1BTIxOjAwBTIxOjE1BTIxOjMwBTIxOjQ1BTIyOjAwBTIyOjE1BTIyOjMwBTIyOjQ1BTIzOjAwBTIzOjE1BTIzOjMwBTIzOjQ1FCsDYGdnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2RkAgwPFgYeBGhyZWZkHgVzdHlsZQUmY3Vyc29yOmhlbHA7dGV4dC1kZWNvcmF0aW9uOnVuZGVybGluZTseC29ubW91c2VvdmVyBYYCcG9wdXAoJzxwPjxiPkFmdGVyIEhvdXIgT3B0aW9uczwvYj48YnIgLz5JZiB5b3UgcmVxdWlyZSBwaWNrdXAgb3IgZHJvcCBvZmYgb3B0aW9uIG91dHNpZGUgb3VyIG5vcm1hbCBidXNpbmVzcyBob3Vycywgd2UgYXJlIGFibGUgdG8gYWNjb21tb2RhdGUgeW91ciByZXF1ZXN0LjwvcD48cD5BICQyNSBmZWUgYXBwbGllcy4gUGlja3VwIHRpbWVzIG91dHNpZGUgb3VyIG5vcm1hbCBidXNpbmVzcyBob3VycyAoNjowMCBhLm0uIHRvIDY6NTkgcC5tLikuPC9wPicpO2QCDw9kFgJmDxAPFgQfA2cfBGdkEBUPFSAtIFNlbGVjdCBMb2NhdGlvbiAtIBBBdWNrbGFuZCBBaXJwb3J0DUF1Y2tsYW5kIENpdHkSV2VsbGluZ3RvbiBBaXJwb3J0D1dlbGxpbmd0b24gQ2l0eQZQaWN0b24MUGljdG9uIEZlcnJ5EEJsZW5oZWltIEFpcnBvcnQOTmVsc29uIEFpcnBvcnQLTmVsc29uIENpdHkUQ2hyaXN0Y2h1cmNoIEFpcnBvcnQRQ2hyaXN0Y2h1cmNoIENpdHkJR3JleW1vdXRoElF1ZWVuc3Rvd24gQWlycG9ydA9RdWVlbnN0b3duIENpdHkVDwADOTkxAzk5MAM5OTgDOTg3Azk2OAQxMDAyAzk3NAQxMDAwAzk2OQQxMDAzAzk3MAM5ODkEMTAwNQM5ODMUKwMPZ2dnZ2dnZ2dnZ2dnZ2dnFgECAWQCEQ8PFgYfBQsrBN8HHwYCBh8HAhNkFgJmD2QWAmYPZBYKAgUPEGRkFgECFWQCBw8QZGQWAQIJZAIJDxBkDxYDZgIBAgIWAxAFAjEzBQQyMDEzZxAFAjE0BQQyMDE0ZxAFAjE1BQQyMDE1ZxYBZmQCCw8QZBAVYAoxMjowMCBhLm0uCjEyOjE1IGEubS4KMTI6MzAgYS5tLgoxMjo0NSBhLm0uCjAxOjAwIGEubS4KMDE6MTUgYS5tLgowMTozMCBhLm0uCjAxOjQ1IGEubS4KMDI6MDAgYS5tLgowMjoxNSBhLm0uCjAyOjMwIGEubS4KMDI6NDUgYS5tLgowMzowMCBhLm0uCjAzOjE1IGEubS4KMDM6MzAgYS5tLgowMzo0NSBhLm0uCjA0OjAwIGEubS4KMDQ6MTUgYS5tLgowNDozMCBhLm0uCjA0OjQ1IGEubS4KMDU6MDAgYS5tLgowNToxNSBhLm0uCjA1OjMwIGEubS4KMDU6NDUgYS5tLgowNjowMCBhLm0uCjA2OjE1IGEubS4KMDY6MzAgYS5tLgowNjo0NSBhLm0uCjA3OjAwIGEubS4KMDc6MTUgYS5tLgowNzozMCBhLm0uCjA3OjQ1IGEubS4KMDg6MDAgYS5tLgowODoxNSBhLm0uCjA4OjMwIGEubS4KMDg6NDUgYS5tLgowOTowMCBhLm0uCjA5OjE1IGEubS4KMDk6MzAgYS5tLgowOTo0NSBhLm0uCjEwOjAwIGEubS4KMTA6MTUgYS5tLgoxMDozMCBhLm0uCjEwOjQ1IGEubS4KMTE6MDAgYS5tLgoxMToxNSBhLm0uCjExOjMwIGEubS4KMTE6NDUgYS5tLgoxMjowMCBwLm0uCjEyOjE1IHAubS4KMTI6MzAgcC5tLgoxMjo0NSBwLm0uCjAxOjAwIHAubS4KMDE6MTUgcC5tLgowMTozMCBwLm0uCjAxOjQ1IHAubS4KMDI6MDAgcC5tLgowMjoxNSBwLm0uCjAyOjMwIHAubS4KMDI6NDUgcC5tLgowMzowMCBwLm0uCjAzOjE1IHAubS4KMDM6MzAgcC5tLgowMzo0NSBwLm0uCjA0OjAwIHAubS4KMDQ6MTUgcC5tLgowNDozMCBwLm0uCjA0OjQ1IHAubS4KMDU6MDAgcC5tLgowNToxNSBwLm0uCjA1OjMwIHAubS4KMDU6NDUgcC5tLgowNjowMCBwLm0uCjA2OjE1IHAubS4KMDY6MzAgcC5tLgowNjo0NSBwLm0uCjA3OjAwIHAubS4KMDc6MTUgcC5tLgowNzozMCBwLm0uCjA3OjQ1IHAubS4KMDg6MDAgcC5tLgowODoxNSBwLm0uCjA4OjMwIHAubS4KMDg6NDUgcC5tLgowOTowMCBwLm0uCjA5OjE1IHAubS4KMDk6MzAgcC5tLgowOTo0NSBwLm0uCjEwOjAwIHAubS4KMTA6MTUgcC5tLgoxMDozMCBwLm0uCjEwOjQ1IHAubS4KMTE6MDAgcC5tLgoxMToxNSBwLm0uCjExOjMwIHAubS4KMTE6NDUgcC5tLhVgBTAwOjAwBTAwOjE1BTAwOjMwBTAwOjQ1BTAxOjAwBTAxOjE1BTAxOjMwBTAxOjQ1BTAyOjAwBTAyOjE1BTAyOjMwBTAyOjQ1BTAzOjAwBTAzOjE1BTAzOjMwBTAzOjQ1BTA0OjAwBTA0OjE1BTA0OjMwBTA0OjQ1BTA1OjAwBTA1OjE1BTA1OjMwBTA1OjQ1BTA2OjAwBTA2OjE1BTA2OjMwBTA2OjQ1BTA3OjAwBTA3OjE1BTA3OjMwBTA3OjQ1BTA4OjAwBTA4OjE1BTA4OjMwBTA4OjQ1BTA5OjAwBTA5OjE1BTA5OjMwBTA5OjQ1BTEwOjAwBTEwOjE1BTEwOjMwBTEwOjQ1BTExOjAwBTExOjE1BTExOjMwBTExOjQ1BTEyOjAwBTEyOjE1BTEyOjMwBTEyOjQ1BTEzOjAwBTEzOjE1BTEzOjMwBTEzOjQ1BTE0OjAwBTE0OjE1BTE0OjMwBTE0OjQ1BTE1OjAwBTE1OjE1BTE1OjMwBTE1OjQ1BTE2OjAwBTE2OjE1BTE2OjMwBTE2OjQ1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IGEubS4KMDU6MDAgYS5tLgowNToxNSBhLm0uCjA1OjMwIGEubS4KMDU6NDUgYS5tLgowNjowMCBhLm0uCjA2OjE1IGEubS4KMDY6MzAgYS5tLgowNjo0NSBhLm0uCjA3OjAwIGEubS4KMDc6MTUgYS5tLgowNzozMCBhLm0uCjA3OjQ1IGEubS4KMDg6MDAgYS5tLgowODoxNSBhLm0uCjA4OjMwIGEubS4KMDg6NDUgYS5tLgowOTowMCBhLm0uCjA5OjE1IGEubS4KMDk6MzAgYS5tLgowOTo0NSBhLm0uCjEwOjAwIGEubS4KMTA6MTUgYS5tLgoxMDozMCBhLm0uCjEwOjQ1IGEubS4KMTE6MDAgYS5tLgoxMToxNSBhLm0uCjExOjMwIGEubS4KMTE6NDUgYS5tLgoxMjowMCBwLm0uCjEyOjE1IHAubS4KMTI6MzAgcC5tLgoxMjo0NSBwLm0uCjAxOjAwIHAubS4KMDE6MTUgcC5tLgowMTozMCBwLm0uCjAxOjQ1IHAubS4KMDI6MDAgcC5tLgowMjoxNSBwLm0uCjAyOjMwIHAubS4KMDI6NDUgcC5tLgowMzowMCBwLm0uCjAzOjE1IHAubS4KMDM6MzAgcC5tLgowMzo0NSBwLm0uCjA0OjAwIHAubS4KMDQ6MTUgcC5tLgowNDozMCBwLm0uCjA0OjQ1IHAubS4KMDU6MDAgcC5tLgowNToxNSBwLm0uCjA1OjMwIHAubS4KMDU6NDUgcC5tLgowNjowMCBwLm0uCjA2OjE1IHAubS4KMDY6MzAgcC5tLgowNjo0NSBwLm0uCjA3OjAwIHAubS4KMDc6MTUgcC5tLgowNzozMCBwLm0uCjA3OjQ1IHAubS4KMDg6MDAgcC5tLgowODoxNSBwLm0uCjA4OjMwIHAubS4KMDg6NDUgcC5tLgowOTowMCBwLm0uCjA5OjE1IHAubS4KMDk6MzAgcC5tLgowOTo0NSBwLm0uCjEwOjAwIHAubS4KMTA6MTUgcC5tLgoxMDozMCBwLm0uCjEwOjQ1IHAubS4KMTE6MDAgcC5tLgoxMToxNSBwLm0uCjExOjMwIHAubS4KMTE6NDUgcC5tLhVgBTAwOjAwBTAwOjE1BTAwOjMwBTAwOjQ1BTAxOjAwBTAxOjE1BTAxOjMwBTAxOjQ1BTAyOjAwBTAy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NSBhLm0uCjA0OjAwIGEubS4KMDQ6MTUgYS5tLgowNDozMCBhLm0uCjA0OjQ1IGEubS4KMDU6MDAgYS5tLgowNToxNSBhLm0uCjA1OjMwIGEubS4KMDU6NDUgYS5tLgowNjowMCBhLm0uCjA2OjE1IGEubS4KMDY6MzAgYS5tLgowNjo0NSBhLm0uCjA3OjAwIGEubS4KMDc6MTUgYS5tLgowNzozMCBhLm0uCjA3OjQ1IGEubS4KMDg6MDAgYS5tLgowODoxNSBhLm0uCjA4OjMwIGEubS4KMDg6NDUgYS5tLgowOTowMCBhLm0uCjA5OjE1IGEubS4KMDk6MzAgYS5tLgowOTo0NSBhLm0uCjEwOjAwIGEubS4KMTA6MTUgYS5tLgoxMDozMCBhLm0uCjEwOjQ1IGEubS4KMTE6MDAgYS5tLgoxMToxNSBhLm0uCjExOjMwIGEubS4KMTE6NDUgYS5tLgoxMjowMCBwLm0uCjEyOjE1IHAubS4KMTI6MzAgcC5tLgoxMjo0NSBwLm0uCjAxOjAwIHAubS4KMDE6MTUgcC5tLgowMTozMCBwLm0uCjAxOjQ1IHAubS4KMDI6MDAgcC5tLgowMjoxNSBwLm0uCjAyOjMwIHAubS4KMDI6NDUgcC5tLgowMzowMCBwLm0uCjAzOjE1IHAubS4KMDM6MzAgcC5tLgowMzo0NSBwLm0uCjA0OjAwIHAubS4KMDQ6MTUgcC5tLgowNDozMCBwLm0uCjA0OjQ1IHAubS4KMDU6MDAgcC5tLgowNToxNSBwLm0uCjA1OjMwIHAubS4KMDU6NDUgcC5tLgowNjowMCBwLm0uCjA2OjE1IHAubS4KMDY6MzAgcC5tLgowNjo0NSBwLm0uCjA3OjAwIHAubS4KMDc6MTUgcC5tLgowNzozMCBwLm0uCjA3OjQ1IHAubS4KMDg6MDAgcC5tLgowODoxNSBwLm0uCjA4OjMwIHAubS4KMDg6NDUgcC5tLgowOTowMCBwLm0uCjA5OjE1IHAubS4KMDk6MzAgcC5tLgowOTo0NSBwLm0uCjEwOjAwIHAubS4KMTA6MTUgcC5tLgoxMDozMCBwLm0uCjEwOjQ1IHAubS4KMTE6MDAgcC5tLgoxMToxNSBwLm0uCjExOjMwIHAubS4KMTE6NDUgcC5tLhVgBTAwOjAwBTAwOjE1BTAwOjMwBTAwOjQ1BTAxOjAwBTAxOjE1BTAxOjMwBTAxOjQ1BTAyOjAwBTAyOjE1BTAyOjMwBTAyOjQ1BTAzOjAwBTAzOjE1BTAzOjMwBTAzOjQ1BTA0OjAwBTA0OjE1BTA0OjMwBTA0OjQ1BTA1OjAwBTA1OjE1BTA1OjMwBTA1OjQ1BTA2OjAwBTA2OjE1BTA2OjMwBTA2OjQ1BTA3OjAwBTA3OjE1BTA3OjMwBTA3OjQ1BTA4OjAwBTA4OjE1BTA4OjMwBTA4OjQ1BTA5OjAwBTA5OjE1BTA5OjMwBTA5OjQ1BTEwOjAwBTEwOjE1BTEwOjMwBTEwOjQ1BTExOjAwBTExOjE1BTExOjMwBTExOjQ1BTEyOjAwBTEyOjE1BTEyOjMwBTEyOjQ1BTEzOjAwBTEzOjE1BTEzOjMwBTEzOjQ1BTE0OjAwBTE0OjE1BTE0OjMwBTE0OjQ1BTE1OjAwBTE1OjE1BTE1OjMwBTE1OjQ1BTE2OjAwBTE2OjE1BTE2OjMwBTE2OjQ1BTE3OjAwBTE3OjE1BTE3OjMwBTE3OjQ1BTE4OjAwBTE4OjE1BTE4OjMwBTE4OjQ1BTE5OjAwBTE5OjE1BTE5OjMwBTE5OjQ1BTIwOjAwBTIwOjE1BTIwOjMwBTIwOjQ1BTIxOjAwBTIxOjE1BTIxOjMwBTIxOjQ1BTIyOjAwBTIyOjE1BTIyOjMwBTIyOjQ1BTIzOjAwBTIzOjE1BTIzOjMwBTIzOjQ1FCsDYGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGdnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaGhoaBYBAihkAgwPFgIfAmhkAgkPFgIfGgUUbWRsRGVhbHNCZWhhdmlvdXJob3RkAhMPZBYCAgEPZBYCZg9kFgICAQ9kFgICAQ8QZGQWAGQCDg8WAh8XBQQyMDEzZAIQDw9kDxAWAWYWARYEHgxEZWZhdWx0VmFsdWUFB2RlZmF1bHQeDlBhcmFtZXRlclZhbHVlZBYBAgNkZBgDBR5fX0NvbnRyb2xzUmVxdWlyZVBvc3RCYWNrS2V5X18WBAUrY3RsMDAkY3RybExvZ2luJGxndkxvZ2luJGxnTG9naW4kUmVtZW1iZXJNZQVfY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRjdHJsRGVmYXVsdFBhZ2VDYXJTZWFyY2hDcml0ZXJpYSRjdHJsSXNsYW5kU2VsZWN0b3IkSW50ZXJJc2xhbmRIaXJlJDAFX2N0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkY3RybERlZmF1bHRQYWdlQ2FyU2VhcmNoQ3JpdGVyaWEkY3RybElzbGFuZFNlbGVjdG9yJEludGVySXNsYW5kSGlyZSQxBV9jdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGN0cmxEZWZhdWx0UGFnZUNhclNlYXJjaENyaXRlcmlhJGN0cmxJc2xhbmRTZWxlY3RvciRJbnRlcklzbGFuZEhpcmUkMQUMY3RsMDAkbXVsTmF2Dw9kAgFkBRNjdGwwMCRtdWxRdWlja0xpbmtzDw9kAgFknV2m5m23f53yf+3TW7Fg7TmbJKQ=',
'__EVENTVALIDATION' => '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',
'ctl00$ctrlLogin$lgvLogin$lgLogin$UserName' => '',
'ctl00$ctrlLogin$lgvLogin$lgLogin$Password' => '',
'ctl00$ctrlLogin$lgvLogin$lgLogin$RememberMe' => 'on',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$hidAfterHourPickupFee' => '',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$hidAfterHourDropoffFee' => '',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$ddVehicleCategory' => '11',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$PickUpDate$ddlOpeningHour' => '',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$PickUpDate$ddlClosingHour' => '',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$PickUpDate$ddlDay' => "$puDateSplit[0]",
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$PickUpDate$ddlMonth' => "$puDateSplit[1]",
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$PickUpDate$ddlYear' => "$puDateSplit[2]",
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$PickUpDate$ddTime' => '10:00',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$DropOffLocation$ctrlLocation' => "$locTwo",
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$DropOffDate$ddlOpeningHour' => '',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$DropOffDate$ddlClosingHour' => '',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$DropOffDate$ddlDay' => "$doDateSplit[0]",
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$DropOffDate$ddlMonth' => "$doDateSplit[1]",
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$DropOffDate$ddlYear' => "$doDateSplit[2]",
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$DropOffDate$ddTime' => '10:00',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$ctrlIslandSelector$InterIslandHire$0' => 'on',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$ctrlIslandSelector$InterIslandHire$1' => 'on',
'ctl00$ContentPlaceHolder1$ctrlDefaultPageCarSearchCriteria$ddTransmission' => '0',
'ctl00$ContentPlaceHolder1$ctrlReloSide$hdnDealType' => 'relo',
'ctl00$ContentPlaceHolder1$ctrlHotDealSide$hdnDealType' => 'relo',
''=>''
);

		$data = $this->scrapeSite($url, $postdata);
                $largeCarArray = @$this->ApexCars($data);
            break;
        }
        echo json_encode($largeCarArray);
		
	}
	
	function scrapeSite($url, $postdata) {
		
		/*$this->load->library('curl'); 
		$this->curl->create($url);
		$this->curl->post($postdata);
		return $this->curl->execute();*/
		$ch = @curl_init();

		if($ch){
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies2.txt'); // set cookie file to given file
			curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies2.txt'); // set same file as cookie jar
			curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.69 Safari/537.36');

			$content = curl_exec($ch);
			$headers = curl_getinfo($ch);				
			
			curl_close($ch);

            if($headers['http_code'] == 200){ 
                return $content;
            }
			else if ($headers['http_code'] == 302){
				$ch = @curl_init();
				curl_setopt($ch, CURLOPT_URL, $headers['redirect_url']);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies2.txt'); // set cookie file to given file
				curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies2.txt'); // set same file as cookie jar
				curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.69 Safari/537.36');

				$content = curl_exec($ch);
				$headers = curl_getinfo($ch);
				
				curl_close($ch);
				
				if($headers['http_code'] == 200){ 
					return $content;
				}
			}
		}
	}
	
	function AceCars($data) {

		$dom = new DOMDocument(); 
		@$dom->loadHTML($data); 
		$tempDom = new DOMDocument(); 
		$carDom = new DOMDocument();
		
		$xpath = new DOMXPath($dom);
		$site = $xpath->query("//div[@id='container']/div[@id='right_column']/div[@class='section']/form/table[@class='displaytable'][2]"); 
		foreach ( $site as $item ) { 
			$tempDom->appendChild($tempDom->importNode($item,true)); 
		}
		$tempDom->saveHTML();
		$carsXpath = new DOMXPath($tempDom);
		
		$results = array();
		
		$cars = $carsXpath->query("tr");

		$i = 1;
		foreach ($cars as $car) {
			if ($i % 2 != 0) {
				$newDom = new DOMDocument; 
				$newDom->appendChild($newDom->importNode($car,true)); 
				$carXpath = new DOMXPath( $newDom ); 
				
				$image = trim($carXpath->query("td/img[1]/@src")->item(0)->nodeValue);
				$title = trim($carXpath->query("td[2]/h3/text()")->item(0)->nodeValue);
				//if "pp_special" in car.extract():
				//    carobj['price'] = car.select("td[3]/div/div/div[4]/div/span[2]/text()").extract()
				//else:
				$price = trim($carXpath->query("td[3]/div/div/div[4]/span[2]/text()")->item(0)->nodeValue);
				$type = trim($carXpath->query("td[2]/p/text()")->item(0)->nodeValue);
				//if "pp_special" in car.extract():
				//    carobj['gearbox'] = car.select("td[2]/ul/li[2]/text()").extract()
				//else:
				$gearbox = trim($carXpath->query("td[2]/ul/li[1]/text()")->item(0)->nodeValue);
				$size = trim($carXpath->query("td/text()[1]")->item(0)->nodeValue);
				
				$results[] = array( 
                'company' => "AceRentals",
				'url' => "http://www.acerentals.co.nz",
				'image' => $image, 
				'title' => $title, 
				'type' => $type, 
				'gearbox' => $gearbox,
				'size' => $size,
				'price' => $price,            
				); 
			}
			$i++;
		}
		return $results;
	}
    
    function PegasusCars($data) {
    	$dom = new DOMDocument();
	@$dom->loadHTML($data);
	$tempDom = new DOMDocument();
	$carDom = new DOMDocument(); 
	$xpath = new DOMXPath($dom); 
	
	$site = $xpath->query("//div[@id='content']/div[@id='book-col-2']/div[@id='vehicle_holder']/ul[@class='vehicles']");
        foreach ( $site as $item ) {
            $tempDom->appendChild($tempDom->importNode($item,true));
        }

        $tempDom->saveHTML();
        $carsXpath = new DOMXPath($tempDom);
        $results = array();

        $cars = $carsXpath->query("//li[@class='clearfix']");

        foreach ($cars as $car) {
                $newDom = new DOMDocument;
                $newDom->appendChild($newDom->importNode($car,true));
                $carXpath = new DOMXPath( $newDom );

                $image = trim($carXpath->query("div[@class='car-col-1']/img/@src")->item(0)->nodeValue);
                $title = trim($carXpath->query("h3[@class='blue_bar']/text()")->item(0)->nodeValue);
                $price = trim($carXpath->query("div[@class='car-col-1']/p/text()")->item(0)->nodeValue);
              
                
                $type = trim($carXpath->query("div[@class='car-col-2']/ul/li[3]/text()")->item(0)->nodeValue);
                $gearbox = trim($carXpath->query("div[@class='car-col-2']/ul/li[2]/text()")->item(0)->nodeValue);
                $size = trim($carXpath->query("div[@class='car-col-2']/div/p[1]/text()")->item(0)->nodeValue);
                
                if ($price != "N/A") {
                    $results[] = array(
                        'company' => "Pegasus",
			'url' => "http://www.rentalcars.co.nz",
                        'image' => $image,
                        'title' => $title,
                        'type' => $type,
                        'gearbox' => $gearbox,
                        'size' => $size,
                        'price' => $price,
                    );   
                }
        } 
    	return $results;
    }
    
    function OmegaCars($data) {	
		
        $dom = new DOMDocument();
		@$dom->loadHTML($data);
		$tempDom = new DOMDocument();
		$carDom = new DOMDocument(); 
		$xpath = new DOMXPath($dom); 
		$site = $xpath->query("//div[@class='content_block content_list']");  

		foreach ( $site as $item ) {         
			$tempDom->appendChild($tempDom->importNode($item,true));    
		}         
		$tempDom->saveHTML();     
		$carsXpath = new DOMXPath($tempDom);
		$results = array();  
		 
		$cars = $carsXpath->query("//div[contains(@class,'content_row list_item fleet_item')]");
			
		foreach ($cars as $car) {
			$newDom = new DOMDocument;
			$newDom->appendChild($newDom->importNode($car,true));
			$carXpath = new DOMXPath( $newDom );
			$image = "http://www.omegarentals.co.nz/" . trim($carXpath->query("div[@class='list_left']/div[@class='list_image']/img/@src")->item(0)->nodeValue);
			$type = trim($carXpath->query("div[@class='list_left']/div[@class='list_title']/h3/text()")->item(0)->nodeValue);
			$price = trim($carXpath->query("div[@class='list_right']/div[@class='list_price']/div[@class='price']/text()")->item(0)->nodeValue);
			$title = trim($carXpath->query("div[@class='list_left']/div[@class='list_title']/h2/text()")->item(0)->nodeValue);
			$gearbox = $carXpath->query("div[@class='list_right']/div[@class='list_features']/div[@class='feature'][1]/text()[contains(.,'Manual') or contains(.,'Automatic')]");
			$gearbox = $gearbox->length ? trim($gearbox->item(0)->nodeValue) : "N/A";
			$size = $carXpath->query("div[@class='list_right']/div[@class='list_icons']/div[@class='icon adult']");
			$size = $size->length;
			
            $results[] = array(
            'company' => "Omega",
	    'url' => "http://www.omegarentalcars.co.nz",
            'image' => $image,
            'title' => $title,
            'type' => $type,
            'gearbox' => $gearbox,
            'size' => $size,
            'price' => $price,
            );
        }

        return $results;
    }
    
    function ThriftyCars($data) {	

        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        $tempDom = new DOMDocument();
        $carDom = new DOMDocument();
        $xpath = new DOMXPath($dom);
        
        $site = $xpath->query("//table/tr[position()>2]");
        
        $count = $site->length;        
        $i = 0;
                    
		$results = array();
		
        while ($i < $count) {
            $children = $site->item($i)->childNodes;
            $tmp_doc = new DOMDocument();
 
            //save child nodes to a new dom
            for($j = 0; $j < $children->length; $j++){  
                $tmp_doc->appendChild($tmp_doc->importNode($children->item($j), true));     
                $tmp_doc->saveHTML();     
            }
            $carXpath = new DOMXPath($tmp_doc);
            
            $image = trim($carXpath->query("img/@src")->item(0)->nodeValue);
            $title = trim($carXpath->query("h3/text()")->item(0)->nodeValue);
            $type =  trim($carXpath->query("h1/text()")->item(0)->nodeValue);
            
            $i++;
            
            $children = $site->item($i)->childNodes;
            $tmp_doc = new DOMDocument();
 
            //save child nodes to a new dom
            for($j = 0; $j < $children->length; $j++){ 
                $tmp_doc->appendChild($tmp_doc->importNode($children->item($j), true));     
                $tmp_doc->saveHTML();     
            }
            $carXpath = new DOMXPath($tmp_doc);
            
            $gearbox =  trim($carXpath->query("p/text()[1]")->item(0)->nodeValue);
            $size =  trim($carXpath->query("p/text()[2]")->item(0)->nodeValue);
			$price = $carXpath->query("//h1/text()");
            $price = $price->length ? $price->item(0)->nodeValue : "N/A";
            $i=$i+2;
			if ($price != "N/A")
			{
				$results[] = array(
					'company' => "Thrifty",
					'url' => "http://www.thrifty.co.nz",
					'image' => $image,
					'title' => $title,
					'type' => $type,
					'gearbox' => $gearbox,
					'size' => $size,
					'price' => $price
				);
			}			
        }   
		
        return $results;
    }

    function BritzCars($data) {
        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        $tempDom = new DOMDocument();
        $carDom = new DOMDocument();
        $xpath = new DOMXPath($dom);

        $site = $xpath->query("//form[@id='form1']/div[@id='vehicleSelectionContainer']/div[@id='vehicleCatalogContainer']/ul[@id='vehicleCatalog']");

        foreach ( $site as $item ) {
            $tempDom->appendChild($tempDom->importNode($item,true));
        }

        $tempDom->saveHTML();
        $carsXpath = new DOMXPath($tempDom);
        $results = array();

            $cars = $carsXpath->query("//li[@class='Collapse element avail']");

            foreach ($cars as $car) {
                $newDom = new DOMDocument;
                $newDom->appendChild($newDom->importNode($car,true));
                $carXpath = new DOMXPath( $newDom );

                $image = trim($carXpath->query("div[@class='VehicleItem']/a[@class='VehicleThumb PopUp']/img/@src")->item(0)->nodeValue);
                $title = trim($carXpath->query("div[@class='VehicleItem']/div[@class='VehicleFeatures']/a[@class='PopUp']/text()")->item(0)->nodeValue);
                $price = $carXpath->query("div[@class='PriceDetailsList NoFreeDays']/table[@class='chargesTable']/tbody/tr[1]/td[@class='dpd']/text()");
                $price = $price->length ? trim($price->item(0)->nodeValue) : "N/A";
                
                $type = "N/A";
                $gearbox = "N/A";
                $size = "N/A"; 
                
                if ($price != "N/A") {
                    $results[] = array(
                        'company' => "Britz",
			'url' => "http://www.britz.co.nz",
                        'image' => $image,
                        'title' => $title,
                        'type' => $type,
                        'gearbox' => $gearbox,
                        'size' => $size,
                        'price' => $price,
                    );   
                }
            } 

            return $results;
    }

    function BudgetCars($data) {
        $dom = new DOMDocument();
		@$dom->loadHTML($data);
		$tempDom = new DOMDocument();
		$carDom = new DOMDocument();
		$xpath = new DOMXPath($dom);
		
		$site = $xpath->query("//div[@id='wrapper']/div[@id='reservation-details']/div[@id='ReservationChooseVehicle']/div[@class='light-orange-box cf']/div[@class='vehicle-list']");
		
		foreach ( $site as $item ) { 
			$tempDom->appendChild($tempDom->importNode($item,true)); 
		}
		$tempDom->saveHTML();
		$carsXpath = new DOMXPath($tempDom);
		
		$cars = $carsXpath->query("div");
		
		$count = $cars->length;    

		$i = 0;
		$results = array();			
		while ($i < $count) {
			$children = $cars->item($i)->childNodes;
			$tmp_doc = new DOMDocument();
 
			//save child nodes to a new dom
			for($j = 0; $j < $children->length; $j++){  
				$tmp_doc->appendChild($tmp_doc->importNode($children->item($j), true));     
				$tmp_doc->saveHTML();     
			}
			//echo $i . $tmp_doc->saveHTML();   
			$carXpath = new DOMXPath($tmp_doc);
			
			$image = "https://www.budget.co.nz/" . trim($carXpath->query("//img/@src")->item(0)->nodeValue);
			
			$i++;
			
			$children = $cars->item($i)->childNodes;
			$tmp_doc = new DOMDocument();
 
			//save child nodes to a new dom
			for($j = 0; $j < $children->length; $j++){ 
				$tmp_doc->appendChild($tmp_doc->importNode($children->item($j), true));     
				$tmp_doc->saveHTML();     
			}

			$carXpath = new DOMXPath($tmp_doc);            

			$title = trim($carXpath->query("//div[@class='brief']/strong/text()[1]")->item(0)->nodeValue);
			$type =  trim($carXpath->query("//div[@class='brief']/strong/text()[2]")->item(0)->nodeValue);
			$gearbox = $carXpath->query("//div[@class='brief']/p/span/text()[1]");
            $gearbox = $gearbox->length ? $gearbox->item(0)->nodeValue : "N/A";
			
			$i++;
			
			$children = $cars->item($i)->childNodes;
			$tmp_doc = new DOMDocument();
 
			//save child nodes to a new dom
			for($j = 0; $j < $children->length; $j++){ 
				$tmp_doc->appendChild($tmp_doc->importNode($children->item($j), true));     
				$tmp_doc->saveHTML();     
			}
  
			$carXpath = new DOMXPath($tmp_doc);
			
			$price =  trim($carXpath->query("//div[3]/text()")->item(0)->nodeValue);
			
			$i=$i+3;
			
			$children = $cars->item($i)->childNodes;
			$tmp_doc = new DOMDocument();
 
			//save child nodes to a new dom
			for($j = 0; $j < $children->length; $j++){ 
				$tmp_doc->appendChild($tmp_doc->importNode($children->item($j), true));     
				$tmp_doc->saveHTML();     
			}

			$carXpath = new DOMXPath($tmp_doc);
			
			$size = $carXpath->query("//div[2]/div/img[@src='/images/ico_person.gif']/@src");
			$size = $size->length;
			$i=$i+2;
			
			$results[] = array(
				'company' => "Budget",
				'url' => "http://www.budget.co.nz",
				'image' => $image,
				'title' => $title,
				'type' => $type,
				'gearbox' => $gearbox,
				'size' => $size,
				'price' => $price
			);             
		} 

        return $results;
    }

    function JucyCars($data) {
    }

    function ApexCars($data) {
        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        $tempDom = new DOMDocument();
        $carDom = new DOMDocument();
        $xpath = new DOMXPath($dom);
        
        $site = $xpath->query("//div[@id='containerFooter']/div[@id='containerMain']/div[@id='containerHeader']/form[@id='aspnetForm']/div[@id='contentMain']/div[3]/div[@class='mainRightContent']/div[@id='ctl00_ContentPlaceHolder1_upCars']");

        foreach ( $site as $item ) {
            $tempDom->appendChild($tempDom->importNode($item,true));
        }

        $tempDom->saveHTML();
        $carsXpath = new DOMXPath($tempDom);
        $results = array();

        $cars = $carsXpath->query("//div[@class='dvVehicleItem']");

        foreach ($cars as $car) {
                $newDom = new DOMDocument;
                $newDom->appendChild($newDom->importNode($car,true));
                $carXpath = new DOMXPath( $newDom );

                $image = trim($carXpath->query("table/tr[2]/td[1]/div[3]/img/@src")->item(0)->nodeValue);
                $title = trim($carXpath->query("table/tr[2]/td[1]/div[@class='h3 clean']/text()")->item(0)->nodeValue);
                $price = trim($carXpath->query("table/tr[2]/td[3]/div/div[@class='dvBoxA']/span[@class='dvRate']/text()")->item(0)->nodeValue);


                $type =  trim($carXpath->query("table/tr[2]/td[1]/div[@class='h2']/text()")->item(0)->nodeValue);
                $gearbox = "N/A"; //info is there, just hard to get
                $size = "N/A"; //info is there, just hard to get

                    $results[] = array(
                        'company' => "Apex",
                        'url' => "http://www.apexrentals.co.nz",
                        'image' => $image,
                        'title' => $title,
                        'type' => $type,
                        'gearbox' => $gearbox,
                        'size' => $size,
                        'price' => $price,
                    );  
        }

        return $results;
    }
}
?>
