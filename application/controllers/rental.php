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
        
        	if ($this->form_validation->run() === FALSE) {
            		// load values for the view
            		$this->load->vars($data);

            		// get the view section
            		$sections = array(
                		'content'       => 'rental/index',
            		);  
            		$this->template->load('templates/default', $sections);
        	} else {
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
	
	public function contact() {
		$data['page_title'] = $this->lang->line('page_title');
		$this->load->vars($data);

		 // get the view section
        	$sections = array(
            		'content'       => 'rental/contact',
        	);  
        	$this->template->load('templates/default', $sections);
	}
	
	public function about() {
		$data['page_title'] = $this->lang->line('page_title');
		$this->load->vars($data);

        	// get the view section
        	$sections = array(
            		'content'       => 'rental/about',
        	);  
        	$this->template->load('templates/default', $sections);
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
				
				$url = 'https://www.acerentalcars.co.nz/inet/formprocess.php';
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
					'promocode' => ''
				);
			
				$data = $this->scrapeSite($url, $postdata);
				$largeCarArray = @$this->AceCars($data);
			break;
		
			case 'Omega':
				
				$url = 'https://www.omegarentalcars.com/home/QuoteForm';
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
					
				$url = "http://www.rentalcars.co.nz/bookings/?PickUp=". $locOne ."&DropOff=". $locTwo ."&PickUpDate=". $fDateReplaced ."&PickUpTime=1000&DropOffDate=". $tDateReplaced ."&DropOffTime=1000&CarType=1&MinAge=26&action_doQuoteForm=Quote";

				$data = $this->simpleScrape($url);
				$largeCarArray = @$this->PegasusCars($data);
			
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
					'ctl00$cp1$pagecontrols_2013_reservationbooking$pagecontrols_2013_reservationquickbookingnz$txtIATA' => ''
				);
				
				$data = $this->scrapeSite($url, $postdata);								
				$largeCarArray = @$this->BudgetCars($data);
			break;
			
			case 'Apex':
				
				$puDateSplit = explode("/", $pudate);
				$doDateSplit = explode("/", $dodate);
				
				$url = 'http://www.apexrentals.co.nz/cars.aspx';
				$postdata = array(
					'ctl00$ajaxScripManager' => 'ctl00$ctrlSearchCriteria$upSearchCriteria|ctl00$ctrlSearchCriteria$lnkBtnGO',
					'ctl00_ajaxScripManager_HiddenField' => ';;AjaxControlToolkit, Version=1.0.20229.30882, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:a18505d4-ae51-4ae9-bc76-a8f4a41ab5bb:865923e8:91bd373d:8e72a662:411fea1c:acd642d2:596d588c:77c58d20:14b56adc:269a19ae:ff62b0be:e7c87f07:d72169a4',
					'__EVENTTARGET' => 'ctl00$ctrlSearchCriteria$lnkBtnGO',
					'__EVENTARGUMENT' => '',
					'__VIEWSTATE' => '/wEPDwUKLTkzMzA3NjUxNQ8WBB4PQ3VycmVudFZpZXdNb2RlKClMQVBFWC5jYXJzK2VNb2RlLCBBUEVYLCBWZXJzaW9uPTEuMC4wLjAsIEN1bHR1cmU9bmV1dHJhbCwgUHVibGljS2V5VG9rZW49bnVsbAtWZWhpY2xlTGlzdB4Pc2VsZWN0ZWRWZWhpY2xlZBYCZg9kFgICAw9kFhACCg8WAh4FY2xhc3MFDHV0aWxpdHlSaWdodBYEAgEPZBYCZg9kFgICAQ9kFgJmD2QWBAIJD2QWAgIDD2QWAmYPZBYCZg9kFgICAQ9kFgJmD2QWAgIBD2QWAgIBD2QWAmYPZBYCAgEPZBYCAg8PEA8WAh4HQ2hlY2tlZGdkZGRkAg0PZBYCAgMPZBYCZg9kFgJmD2QWAgIDDxYCHgdWaXNpYmxlaGQCAw9kFgJmD2QWAgIBDxYCHgRUZXh0ZWQCDA9kFgJmD2QWAmYPZBYKAgkPZBYCZg8QDxYEHgxBdXRvUG9zdEJhY2toHgtfIURhdGFCb3VuZGcWAh4Ib25jaGFuZ2UFLmxvY0NoYW5nZSgnUFUnLCcnLCdjdGwwMF9jdHJsU2VhcmNoQ3JpdGVyaWEnKTsQFQ8VIC0gU2VsZWN0IExvY2F0aW9uIC0gEEF1Y2tsYW5kIEFpcnBvcnQNQXVja2xhbmQgQ2l0eRJXZWxsaW5ndG9uIEFpcnBvcnQPV2VsbGluZ3RvbiBDaXR5BlBpY3RvbgxQaWN0b24gRmVycnkQQmxlbmhlaW0gQWlycG9ydA5OZWxzb24gQWlycG9ydAtOZWxzb24gQ2l0eRRDaHJpc3RjaHVyY2ggQWlycG9ydBFDaHJpc3RjaHVyY2ggQ2l0eQlHcmV5bW91dGgSUXVlZW5zdG93biBBaXJwb3J0D1F1ZWVuc3Rvd24gQ2l0eRUPAAM5OTEDOTkwAzk5OAM5ODcDOTY4BDEwMDIDOTc0BDEwMDADOTY5BDEwMDMDOTcwAzk4OQQxMDA1Azk4MxQrAw9nZ2dnZ2dnZ2dnZ2dnZ2cWAQIBZAILD2QWAmYPEA8WBB8GaB8HZxYCHwgFLmxvY0NoYW5nZSgnRE8nLCcnLCdjdGwwMF9jdHJsU2VhcmNoQ3JpdGVyaWEnKTsQFQ8VIC0gU2VsZWN0IExvY2F0aW9uIC0gEEF1Y2tsYW5kIEFpcnBvcnQNQXVja2xhbmQgQ2l0eRJXZWxsaW5ndG9uIEFpcnBvcnQPV2VsbGluZ3RvbiBDaXR5BlBpY3RvbgxQaWN0b24gRmVycnkQQmxlbmhlaW0gQWlycG9ydA5OZWxzb24gQWlycG9ydAtOZWxzb24gQ2l0eRRDaHJpc3RjaHVyY2ggQWlycG9ydBFDaHJpc3RjaHVyY2ggQ2l0eQlHcmV5bW91dGgSUXVlZW5zdG93biBBaXJwb3J0D1F1ZWVuc3Rvd24gQ2l0eRUPAAM5OTEDOTkwAzk5OAM5ODcDOTY4BDEwMDIDOTc0BDEwMDADOTY5BDEwMDMDOTcwAzk4OQQxMDA1Azk4MxQrAw9nZ2dnZ2dnZ2dnZ2dnZ2cWAQIBZAINDw8WAh4EQ29kZQspZUFQRVguVmVoaWNsZVJlcXVlc3RXcmFwcGVyK2VMb2NhdGlvbkNvZGUsIEFQRVgsIFZlcnNpb249MS4wLjAuMCwgQ3VsdHVyZT1uZXV0cmFsLCBQdWJsaWNLZXlUb2tlbj1udWxs3wdkFgRmDw8WBB4IQ3NzQ2xhc3MFCXRieFB1RGF0ZR4EXyFTQgICFgIeBm9uYmx1cgUnY2hlY2tEYXRlKCdjdGwwMF9jdHJsU2VhcmNoQ3JpdGVyaWFfJyk7ZAICDxAPFgYfBmgfCgUNdGJ4UHVEYXRlVGltZR8LAgIWAh8IBS5sb2NDaGFuZ2UoJ1B1JywnJywnY3RsMDBfY3RybFNlYXJjaENyaXRlcmlhJyk7EBVgCjEyOjAwIGEubS4KMTI6MTUgYS5tLgoxMjozMCBhLm0uCjEyOjQ1IGEubS4KMDE6MDAgYS5tLgowMToxNSBhLm0uCjAxOjMwIGEubS4KMDE6NDUgYS5tLgowMjowMCBhLm0uCjAyOjE1IGEubS4KMDI6MzAgYS5tLgowMjo0NSBhLm0uCjAzOjAwIGEubS4KMDM6MTUgYS5tLgowMzozMCBhLm0uCjAzOjQ1IGEubS4KMDQ6MDAgYS5tLgowNDoxNSBhLm0uCjA0OjMwIGEubS4KMDQ6NDUgYS5tLgowNTowMCBhLm0uCjA1OjE1IGEubS4KMDU6MzAgYS5tLgowNTo0NSBhLm0uCjA2OjAwIGEubS4KMDY6MTUgYS5tLgowNjozMCBhLm0uCjA2OjQ1IGEubS4KMDc6MDAgYS5tLgow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OjQ1IGEubS4KMDU6MDAgYS5tLgowNToxNSBhLm0uCjA1OjMwIGEubS4KMDU6NDUgYS5tLgowNjowMCBhLm0uCjA2OjE1IGEubS4KMDY6MzAgYS5tLgowNjo0NSBhLm0uCjA3OjAwIGEubS4KMDc6MTUgYS5tLgowNzozMCBhLm0uCjA3OjQ1IGEubS4KMDg6MDAgYS5tLgowODoxNSBhLm0uCjA4OjMwIGEubS4KMDg6NDUgYS5tLgowOTowMCBhLm0uCjA5OjE1IGEubS4KMDk6MzAgYS5tLgowOTo0NSBhLm0uCjEwOjAwIGEubS4KMTA6MTUgYS5tLgoxMDozMCBhLm0uCjEwOjQ1IGEubS4KMTE6MDAgYS5tLgoxMToxNSBhLm0uCjExOjMwIGEubS4KMTE6NDUgYS5tLgoxMjowMCBwLm0uCjEyOjE1IHAubS4KMTI6MzAgcC5tLgoxMjo0NSBwLm0uCjAxOjAwIHAubS4KMDE6MTUgcC5tLgowMTozMCBwLm0uCjAxOjQ1IHAubS4KMDI6MDAgcC5tLgowMjoxNSBwLm0uCjAyOjMwIHAubS4KMDI6NDUgcC5tLgowMzowMCBwLm0uCjAzOjE1IHAubS4KMDM6MzAgcC5tLgowMzo0NSBwLm0uCjA0OjAwIHAubS4KMDQ6MTUgcC5tLgowNDozMCBwLm0uCjA0OjQ1IHAubS4KMDU6MDAgcC5tLgowNToxNSBwLm0uCjA1OjMwIHAubS4KMDU6NDUgcC5tLgowNjowMCBwLm0uCjA2OjE1IHAubS4KMDY6MzAgcC5tLgowNjo0NSBwLm0uCjA3OjAwIHAubS4KMDc6MTUgcC5tLgowNzozMCBwLm0uCjA3OjQ1IHAubS4KMDg6MDAgcC5tLgowODoxNSBwLm0uCjA4OjMwIHAubS4KMDg6NDUgcC5tLgowOTowMCBwLm0uCjA5OjE1IHAubS4KMDk6MzAgcC5tLgowOTo0NSBwLm0uCjEwOjAwIHAubS4KMTA6MTUgcC5tLgoxMDozMCBwLm0uCjEwOjQ1IHAubS4KMTE6MDAgcC5tLgoxMToxNSBwLm0uCjExOjMwIHAubS4KMTE6NDUgcC5tLhVgBTAwOjAwBTAwOjE1BTAwOjMwBTAwOjQ1BTAxOjAwBTAxOjE1BTAxOjMwBTAxOjQ1BTAyOjAw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MzAgYS5tLgowMjo0NSBhLm0uCjAzOjAwIGEubS4KMDM6MTUgYS5tLgowMzozMCBhLm0uCjAzOjQ1IGEubS4KMDQ6MDAgYS5tLgowNDoxNSBhLm0uCjA0OjMwIGEubS4KMDQ6NDUgYS5tLgowNTowMCBhLm0uCjA1OjE1IGEubS4KMDU6MzAgYS5tLgowNTo0NSBhLm0uCjA2OjAwIGEubS4KMDY6MTUgYS5tLgowNjozMCBhLm0uCjA2OjQ1IGEubS4KMDc6MDAgYS5tLgowNzoxNSBhLm0uCjA3OjMwIGEubS4KMDc6NDUgYS5tLgowODowMCBhLm0uCjA4OjE1IGEubS4KMDg6MzAgYS5tLgowODo0NSBhLm0uCjA5OjAwIGEubS4KMDk6MTUgYS5tLgowOTozMCBhLm0uCjA5OjQ1IGEubS4KMTA6MDAgYS5tLgoxMDoxNSBhLm0uCjEwOjMwIGEubS4KMTA6NDUgYS5tLgoxMTowMCBhLm0uCjExOjE1IGEubS4KMTE6MzAgYS5tLgoxMTo0NSBhLm0uCjEyOjAwIHAubS4KMTI6MTUgcC5tLgoxMjozMCBwLm0uCjEyOjQ1IHAubS4KMDE6MDAgcC5tLgowMToxNSBwLm0uCjAxOjMwIHAubS4KMDE6NDUgcC5tLgowMjowMCBwLm0uCjAyOjE1IHAubS4KMDI6MzAgcC5tLgowMjo0NSBwLm0uCjAzOjAwIHAubS4KMDM6MTUgcC5tLgowMzozMCBwLm0uCjAzOjQ1IHAubS4KMDQ6MDAgcC5tLgowNDoxNSBwLm0uCjA0OjMwIHAubS4KMDQ6NDUgcC5tLgowNTowMCBwLm0uCjA1OjE1IHAubS4KMDU6MzAgcC5tLgowNTo0NSBwLm0uCjA2OjAwIHAubS4KMDY6MTUgcC5tLgowNjozMCBwLm0uCjA2OjQ1IHAubS4KMDc6MDAgcC5tLgowNzoxNSBwLm0uCjA3OjMwIHAubS4KMDc6NDUgcC5tLgowODowMCBwLm0uCjA4OjE1IHAubS4KMDg6MzAgcC5tLgowODo0NSBwLm0uCjA5OjAwIHAubS4KMDk6MTUgcC5tLgowOTozMCBwLm0uCjA5OjQ1IHAubS4KMTA6MDAgcC5tLgoxMDoxNSBwLm0uCjEwOjMwIHAubS4KMTA6NDUgcC5tLgoxMTowMCBwLm0uCjExOjE1IHAubS4KMTE6MzAgcC5tLgoxMTo0NSBwLm0uFWAFMDA6MDAFMDA6MTUFMDA6MzAFMDA6NDUFMDE6MDAFMDE6MTUFMDE6MzAFMDE6NDUFMDI6MDAFMDI6MTUFMDI6MzAFMDI6NDUFMDM6MDAFMDM6MTUFMDM6MzAFMDM6NDUFMDQ6MDAFMDQ6MTUFMDQ6MzAFMDQ6NDUFMDU6MDAFMDU6MTUFMDU6MzAFMDU6NDUFMDY6MDAFMDY6MTUFMDY6MzAFMDY6NDUFMDc6MDAFMDc6MTUFMDc6MzAFMDc6NDUFMDg6MDAFMDg6MTUFMDg6MzAFMDg6NDUFMDk6MDAFMDk6MTUFMDk6MzAFMDk6NDUFMTA6MDAFMTA6MTUFMTA6MzAFMTA6NDUFMTE6MDAFMTE6MTUFMTE6MzAFMTE6NDUFMTI6MDAFMTI6MTUFMTI6MzAFMTI6NDUFMTM6MDAFMTM6MTUFMTM6MzAFMTM6NDUFMTQ6MDAFMTQ6MTUFMTQ6MzAFMTQ6NDUFMTU6MDAFMTU6MTUFMTU6MzAFMTU6NDUFMTY6MDAFMTY6MTUFMTY6MzAFMTY6NDUFMTc6MDAFMTc6MTUFMTc6MzAFMTc6NDUFMTg6MDAFMTg6MTUFMTg6MzAFMTg6NDUFMTk6MDAFMTk6MTUFMTk6MzAFMTk6NDUFMjA6MDAFMjA6MTUFMjA6MzAFMjA6NDUFMjE6MDAFMjE6MTUFMjE6MzAFMjE6NDUFMjI6MDAFMjI6MTUFMjI6MzAFMjI6NDUFMjM6MDAFMjM6MTUFMjM6MzAFMjM6NDUUKwNgZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnFgECKGQCBA8WAh8SBQ1TYXQgMDggTm92IDE0ZAIHDxYCHxMFFW1kbERlYWxzQmVoYXZpb3VyMzFlNGQCIg9kFgQCAQ9kFgJmD2QWCgIBD2QWAmYPZBYCZg9kFggCGw8WAh8FBTNGcmkgMTIgRGVjIDIwMTQgYXQgMTA6MDAgYS5tLiBmcm9tIEF1Y2tsYW5kIEFpcnBvcnRkAh0PFgIfBQUxTW9uIDE1IERlYyAyMDE0IGF0IDEwOjAwIGEubS4gdG8gQXVja2xhbmQgQWlycG9ydGQCHw8WAh8FBQYzIGRheXNkAiEPFgIfBQURTm9ydGggSXNsYW5kIE9ubHlkAgMPFgIfBGhkAgkPFgIfEAIPFh5mD2QWBAIDDw8WBB8FBeIBPGRpdiBzdHlsZT0ncGFkZGluZzowIDEwcHggMCAxMHB4Oyc+PGgyIGNsYXNzPSdjYXJzT3RoZXJIMic+T3VyIFBpY2s8L2gyPkEgZ3JlYXQgZGVhbCBpZiB5b3UgYXJlIGxvb2tpbmcgZm9yIHRoZSBwZWFjZSBvZiBtaW5kIG9mIGZ1bGwgaW5zdXJhbmNlIGF0IGEgZGlzY291bnRlZCByYXRlLjwvZGl2PjxkaXYgY2xhc3M9J2xpc3RpbmdEaXZpZGVyJyBzdHlsZT0nd2lkdGg6MTAwJTsnPjwvZGl2Ph8EZ2RkAgUPDxYGHgdPdXJQaWNrZx4NRmFjZWJvb2tMaWtlZGgeC1ZlaGljbGVDb2RlBQQxMTYxZBYIZg8WAh8FBRM8YSBuYW1lPSIxMTYxIj48L2E+ZAICDxYEHw8FGn4vZ3JhcGhpY3MvaG90ZGVhbF90b3AucG5nHwRnZAIDD2QWAgIBD2QWAmYPZBYCAgEPFgIfAgUSdGJsVmVoaWNsZUl0ZW1faG90FgYCAQ9kFgRmD2QWCAIBDxYCHwUFFk5pc3NhbiBUaWlkYSAvIFNpbWlsYXJkAgMPFgIfBQUOTWlkIFNpemUgU2VkYW5kAgYPDxYCHghJbWFnZVVybAVBaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9pbWFnZXMvZW1haWwvaW1hZ2VfZWNvc2VkYW5BLTI0MC5qcGdkZAIIDxYCHwIFEGR2Qm9va05vd0JveF9ob3QWBAIBD2QWBAIBDxYCHwUFUzxpbWcgd2lkdGg9JzE3NScgaGVpZ2h0PScxMjUnIHNyYz0nL2ltYWdlcy9vZmZlcnMvb2ZmZXJfZnJlZS1mdWxsLWluc3VyYW5jZS5wbmcnIC8+ZAIDDxYCHwUFdTxzcGFuIHN0eWxlPSJmb250LXNpemU6MTFweDtsaW5lLWhlaWdodDoxNXB4OyI+VGhpcyBsaW1pdGVkIHNwZWNpYWwgb2ZmZXIgaW5jbHVkZXMgZnVsbCBpbnN1cmFuY2UgLSBOTyBF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',
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnSize' => '',
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnSizeName' => '',
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnPuLoc' => "$locOne",
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnPuOpening' => '06:00:00:00',
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnPuClosing' => '19:00:00:00',
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnDoLoc' => "$locTwo",
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnDoOpening' => '06:00:00:00',
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnDoClosing' => '19:00:00:00',
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnPuDate' => "$pudate 10:00:00 a.m.",
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnDoDate' => "$dodate 10:00:00 a.m.",
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnTrans' => '',
					'ctl00$ContentPlaceHolder1$ctrlHireSummary$hdnIslands' => '0',
					''=>''
				);

				$data = $this->scrapeSite($url, $postdata);
				$largeCarArray = @$this->ApexCars($data);
			break;
		}
		echo json_encode($largeCarArray);
	
	}
	
	function scrapeSite($url, $postdata) {
		$ch = @curl_init();
		if($ch) {
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies2.txt'); // set cookie file to given file
			curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies2.txt'); // set same file as cookie jar
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.69 Safari/537.36');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			$content = curl_exec($ch);
			$headers = curl_getinfo($ch);	

			curl_close($ch);		
		}
		return $content;		
	}
	
	function simpleScrape($url) {
		$ch = curl_init();
		
		if($ch){
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.69 Safari/537.36');
			
			$content = curl_exec($ch);
			$headers = curl_getinfo($ch);
			
			curl_close($ch);
		}
		
		return $content;
	}	
	
	function AceCars($data) {

		$dom = new DOMDocument(); 
		@$dom->loadHTML($data); 
		$tempDom = new DOMDocument(); 
		$carDom = new DOMDocument();
		
		$xpath = new DOMXPath($dom);
		$table = 1;
		if ($xpath->query("//div[@id='container']/div[@id='right_column']/div[@class='section'][1]/form/h3")->item(0)->nodeValue == "Special Rates available now!")
			$table = 2;
			
		$site = $xpath->query("//div[@id='container']/div[@id='right_column']/div[@class='section']/form/table[@class='displaytable'][".$table."]"); 
		
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

				$price = trim($carXpath->query("td[3]/div/div/div[4]/span[2]/text()")->item(0)->nodeValue);
				$price = $amount = filter_var($price,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                                $price = number_format((float)$price, 2, '.', '');
				$type = trim($carXpath->query("td[2]/p/text()")->item(0)->nodeValue);
				$type = trim(str_replace(array( '(', ')', '-' ), '', $type));

				$gearbox = trim($carXpath->query("td[2]/ul/li[1]/text()")->item(0)->nodeValue);
				$size = trim($carXpath->query("td/text()[1]")->item(0)->nodeValue);
				$size = filter_var($size,FILTER_SANITIZE_NUMBER_INT);			
	
				if ($price != "" && $price != "0.00") {
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
		$site = $xpath->query("/html/body/div[@class='extracontentbg']/div[@class='wrapper']/div[@class=' container']/div[@class='main_content inner2 inner5']");
		foreach ( $site as $item ) {
			$tempDom->appendChild($tempDom->importNode($item,true));
		}
		$tempDom->saveHTML();
		$carsXpath = new DOMXPath($tempDom);
		$results = array();
		$cars = $carsXpath->query("//div[position()>1]");
		$count = $cars->length;   
		$i = 0;
		while ($i < $count) {
			$children = $cars->item($i)->childNodes;
			$tmp_doc = new DOMDocument();
			for($j = 0; $j < $children->length; $j++){  
				$tmp_doc->appendChild($tmp_doc->importNode($children->item($j), true));     
				$tmp_doc->saveHTML();     
			}
			$carXpath = new DOMXPath($tmp_doc);
			$image = trim($carXpath->query("//div/div[@class='text_cont']/p[@class='image']/img/@src")->item(0)->nodeValue);
			$size = trim($carXpath->query("//div/div[@class='seating']/ul/li[1]/span")->item(0)->nodeValue);
			
			$i+=2;
			
			$children = $cars->item($i)->childNodes;
			$tmp_doc = new DOMDocument();
			for($j = 0; $j < $children->length; $j++){ 
				$tmp_doc->appendChild($tmp_doc->importNode($children->item($j), true));     
			$tmp_doc->saveHTML();     
			}
			
			$carXpath = new DOMXPath($tmp_doc);
			$title = trim($carXpath->query("//h3/text()")->item(0)->nodeValue);
			$price = trim($carXpath->query("/div[@class='bottom']/h4/text()")->item(0)->nodeValue);
			$price = $amount = filter_var($price,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
			$price = number_format((float)$price, 2, '.', '');
			$type = "N/A";
            $gearbox = "N/A";
		//	if ($price != ""  && $price != "0.00") {
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
		//	}
			$i=$i+1;		
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
			$type = trim(str_replace(array( '(', ')', '-' ), '', $type));
			$price = trim($carXpath->query("div[@class='list_right']/div[@class='list_price']/div[@class='price']/text()")->item(0)->nodeValue);
			$price = $amount = filter_var($price,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                        $price = number_format((float)$price, 2, '.', '');
			$title = trim($carXpath->query("div[@class='list_left']/div[@class='list_title']/h2/text()")->item(0)->nodeValue);
			$gearbox = $carXpath->query("div[@class='list_right']/div[@class='list_features']/div[@class='feature'][1]/text()[contains(.,'Manual') or contains(.,'Automatic')]");
			$gearbox = $gearbox->length ? trim($gearbox->item(0)->nodeValue) : "N/A";
			$size = $carXpath->query("div[@class='list_right']/div[@class='list_icons']/div[@class='icon adult']");
			$size = $size->length;
			$size = filter_var($size,FILTER_SANITIZE_NUMBER_INT);

			if ($price != "" && $price != "0.00") {
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
					$link = trim($carXpath->query("div[@class='VehicleItem']/div[@class='VehicleFeatures']/a[@class='PopUp']/@href")->item(0)->nodeValue);
					
					$carData = @$this->simpleScrape($link);
					
					$carDom = new DOMDocument();
					@$carDom->loadHTML($carData);
					$carDetXpath = new DOMXPath($carDom);
					
					$size = $carDetXpath->query("//div[@class='detail_icons']/span[@class='people_icon_mid']/img")->length;
					if ($size < 1) $size = "N/A";
					$type = trim($carDetXpath->query("//div[@class='carList']/ul/li[1]/text()")->item(0)->nodeValue);
					if ($type == "") $type = "N/A";
					
					$gearbox = trim($carDetXpath->query("//div[@class='carList']/ul/li[3]/text()")->item(0)->nodeValue);
					if ($gearbox == "") $gearbox = "N/A";
					
					
                	$price = $carXpath->query("div[@class='PriceDetailsList NoFreeDays']/table[@class='chargesTable']/tbody/tr[1]/td[@class='dpd']/text()");
                	$price = $price->length ? trim($price->item(0)->nodeValue) : "N/A";
                	$price = $amount = filter_var($price,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                        $price = number_format((float)$price, 2, '.', ''); 

                
                	if ($price != "N/A"  && $price != "0.00") {
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
			$type = trim(str_replace(array( '(', ')', '-' ), '', $type));
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
			$price = $amount = filter_var($price,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                        $price = number_format((float)$price, 2, '.', '');		
	
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
			$size = filter_var($size,FILTER_SANITIZE_NUMBER_INT);
			$i=$i+2;
			
			if ($price != "" && $price != "0.00") {
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
		} 
        	return $results;
    	}

	function ApexCars($data) {
	        $dom = new DOMDocument();
	        @$dom->loadHTML($data);
	        $tempDom = new DOMDocument();
	        $carDom = new DOMDocument();
	        $xpath = new DOMXPath($dom);
        
        	$site = $xpath->query("/html/body/div[@id='containerMain']/div[@id='containerHeader']/form[@id='aspnetForm']/div[@id='contentMain']/div[3]/div[@class='mainRightContent']/div[@id='ctl00_ContentPlaceHolder1_upCars']");
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
        		$image = trim($carXpath->query("//div/table/tr[2]/td[1]/div[2]/img/@src")->item(0)->nodeValue);
                	$type = trim($carXpath->query("//div/table/tr[2]/td[1]/div[1]/text()")->item(0)->nodeValue);
			$title = trim($carXpath->query("//div/table/tr[2]/td[1]/div[2]/div/text()")->item(0)->nodeValue);
                	$price = trim($carXpath->query("//div/table/tr[2]/td[2]/div/div[1]/div[2]/text()")->item(0)->nodeValue);
			$price = $amount = filter_var($price,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                	$price = number_format((float)$price, 2, '.', '');
               
			$gearbox = trim($carXpath->query("//div/table/tr[3]/td[1]/div/table/tr[2]/td/text()")->item(0)->nodeValue);
                	$size = trim($carXpath->query("//div/table/tr[3]/td[1]/div/table/tr[1]/td[2]/text()")->item(0)->nodeValue);
			if ($price != "" && $price != "0.00") {
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
        	}
        	return $results;
    	}
}
?>
