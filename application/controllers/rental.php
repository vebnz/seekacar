<?php

class Rental extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('rental_model');
    }
    
    public function index() {
        $data['locations'] = $this->rental_model->populateFormLocations();
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $data['title'] = 'Rental Search';
        
        $this->form_validation->set_rules('pickuplocation', 'Pick-Up Location', 'required');
        $this->form_validation->set_rules('pickupdate', 'Pick-Up Date', 'required');
        $this->form_validation->set_rules('pickuptime', 'Pick-Up Time', 'required');
        $this->form_validation->set_rules('dropofflocation', 'Drop-Off Location', 'required');
        $this->form_validation->set_rules('dropoffdate', 'Drop-Off Date', 'required');
        $this->form_validation->set_rules('dropofftime', 'Drop-Off Time', 'required');
        
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/header', $data);            
            $this->load->view('rental/index', $data);
            $this->load->view('templates/footer');            
        }
        else
        {
            $this->load->view('templates/header', $data);
            //$this->rental_model->search_cars();
            $data['plocation'] = $this->input->post('pickuplocation');
            $data['dlocation'] = $this->input->post('dropofflocation');
            $data['pudate'] = $this->input->post('pickupdate');
            $data['putime'] = $this->input->post('pickuptime');
            $data['dodate'] = $this->input->post('dropoffdate');
            $data['dotime'] = $this->input->post('dropofftime');
            //$data['cars'] = $this->get_cars();            
            $this->load->view('rental/results', $data);
            $this->load->view('templates/footer'); 
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
            $largeCarArray = array_merge($largeCarArray, @$this->AceCars($data));
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
                $largeCarArray = array_merge($largeCarArray, @$this->OmegaCars($data));
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
                $largeCarArray = array_merge($largeCarArray, @$this->ThriftyCars($data));
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
                $largeCarArray = array_merge($largeCarArray, @$this->BritzCars($data));
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
                $largeCarArray = array_merge($largeCarArray, @$this->BudgetCars($data));
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
                
                $url = 'http://www.apexrentals.co.nz/cars.aspx';
                $postdata = array('ctl00$ajaxScripManager' => 'ctl00$ctrlSearchCriteria$upSearchCriteria|ctl00$ctrlSearchCriteria$imgBtnGO',
                    '__EVENTTARGET' => '',
                    '__EVENTARGUMENT' => '',
                    '__LASTFOCUS' => '',
                    '__VIEWSTATE' => '/wEPDwUKMTU4ODY2MDk2OQ8WAh4PQ3VycmVudFZpZXdNb2RlKClMQVBFWC5jYXJzK2VNb2RlLCBBUEVYLCBWZXJzaW9uPTEuMC4wLjAsIEN1bHR1cmU9bmV1dHJhbCwgUHVibGljS2V5VG9rZW49bnVsbAtWZWhpY2xlTGlzdBYCZg9kFgQCAw9kFgwCBg9kFgICAQ9kFgJmD2QWAmYPZBYOAgEPFgQeA3NyYwUVbmF2aWdhdGlvbi5hc3B4P21pZD0wHgZoZWlnaHQFBzMwOS45cHhkAgMPFgIfAQUVbmF2aWdhdGlvbi5hc3B4P21pZD0xZAIFDxYCHwEFFW5hdmlnYXRpb24uYXNweD9taWQ9MmQCBw8WAh8BBRVuYXZpZ2F0aW9uLmFzcHg/bWlkPTNkAgkPFgIfAQUVbmF2aWdhdGlvbi5hc3B4P21pZD00ZAILDxYCHwEFFW5hdmlnYXRpb24uYXNweD9taWQ9NWQCDQ8WAh8BBRVuYXZpZ2F0aW9uLmFzcHg/bWlkPTZkAgoPZBYCZg9kFgICAQ9kFgJmD2QWAgIFD2QWAgIDD2QWAmYPZBYCZg9kFgICAw8WAh4HVmlzaWJsZWhkAgwPZBYCZg9kFgICAQ8WAh4EVGV4dGVkAg4PZBYCZg9kFgJmD2QWEAIDDxAPFgIeC18hRGF0YUJvdW5kZ2QQFQYJc21hbGwgY2FyCW1pZC1zaXplZAlsYXJnZSBjYXINc3RhdGlvbiB3YWdvbgNtcHYDNFdEFQYBMwE4ATQBOQE1AjEwFCsDBmdnZ2dnZ2RkAgUPZBYCZg8QDxYCHwVnZBAVDxBBdWNrbGFuZCBBaXJwb3J0DUF1Y2tsYW5kIENpdHkSV2VsbGluZ3RvbiBBaXJwb3J0D1dlbGxpbmd0b24gQ2l0eRBXZWxsaW5ndG9uIEZlcnJ5BlBpY3RvbgxQaWN0b24gRmVycnkQQmxlbmhlaW0gQWlycG9ydA5OZWxzb24gQWlycG9ydAtOZWxzb24gQ2l0eRRDaHJpc3RjaHVyY2ggQWlycG9ydBFDaHJpc3RjaHVyY2ggQ2l0eQlHcmV5bW91dGgSUXVlZW5zdG93biBBaXJwb3J0D1F1ZWVuc3Rvd24gQ2l0eRUPAzk5MQM5OTADOTk4Azk4NwQxMDAxAzk2OAQxMDAyAzk3NAQxMDAwAzk2OQQx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YXNlZCBvbiBvdXIgc3RhbmRhcmQgcmF0ZSBvZiAkNDkvZGF5LiBRdW90ZWQgcmF0ZSBpbmNsdWRlcyBHU1QsIHVubGltaXRlZCBrbXMsIGFuZCBzdGFuZGFyZCBpbnN1cmFuY2UgKCQxLDUwMCBleGNlc3MpJykfEQUGa2lsbCgpZAICD2QWAgIBD2QWAmYPFgIfBAUJMjAwNC0yMDA4ZAIDD2QWAgIBD2QWAmYPFgIfBAUJYXV0b21hdGljZAIED2QWAgIBD2QWAmYPFgIfBAUGMTUwMGNjZAIFD2QWBGYPZBYCZg8WBB8QBaECcG9wdXAoJzxiPjIwJSBPZmYgRnVlbDwvYj48YnIgLz48cD5XZSB3aWxsIHNlbGwgeW91IHlvdXIgZmlyc3QgdGFuayBvZiBmdWVsIGF0IGEgMjAlIGRpc2NvdW50IHRvIHRoZSBjdXJyZW50IHByaWNlIG9mIGZ1ZWwgYXZhaWxhYmxlIGF0IHRoZSBjbG9zZXN0IHBldHJvbCBzdGF0aW9uIHRvIHlvdXIgcGlja3VwIGRlcG90LjwvcD48cD5XaGVuIHlvdSByZXR1cm4gdGhlIHZlaGljbGUgdGhlcmUgaXMgbm8gbmVlZCB0byByZWZ1ZWwgaXQuIFdlIGRvIG5vdCByZWZ1bmQgZm9yIHVudXNlZCBmdWVsLjwvcD4nKR8RBQZraWxsKCkWAmYPDxYEHwoFH34vaW1hZ2VzL29mZmVycy8yMC1vZmYtZnVlbC5naWYfA2dkZAICD2QWAmYPZBYQZg8PFgIfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9hZHVsdC5naWZkZAIBDxYCHwQFAng1ZAICDw8WBB8KBS9odHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL2NoaWxkLmdpZh8DaGRkAgMPFgQfBAUCeDAfA2hkAgQPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2VfbGFyZ2UuZ2lmZGQCBQ8WAh8EBQJ4MmQCBg8PFgIfCgU4aHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9zdWl0Y2FzZV9zbWFsbC5naWZkZAIHDxYCHwQFAngyZAIGD2QWAgIBD2QWAmYPDxYCHwQFCUFWQUlMQUJMRWRkAgcPZBYCZg9kFggCAQ8PFgIfEwUEMTE2MWRkAgMPDxYCHxMFBDExNjFkZAIFDw8WAh8TBQQxMTYxZGQCBw8PFgIfEwUEMTE2MWRkAgQPFgIfA2hkAgIPZBYCAgUPDxYEHw5oHw8FBDEzMTFkFgYCAg8WAh8DaGQCAw8WAh8SZRYQZg9kFgRmD2QWAmYPDxYCHwoFIn4vaW1hZ2VzL2NhcnMvc3VwZXJoYXRjaC1zbWFsbC5qcGdkZAIBD2QWAmYPDxYEHwQFEnN1cGVyIGhhdGNoIG1hbnVhbB8TBQQxMzExZGQCAQ9kFgQCAQ9kFgJmDxYCHwQFCkZvcmQgRm9jdXNkAgIPZBYKAgEPDxYCHwQFAyQ1MmRkAgMPDxYCHwoFGn4vZ3JhcGhpY3MvcGVyZGF5X3N0YXIuZ2lmZGQCBQ8WAh8EBQZOWiQzNjRkAgcPFgIfBAUJc2F2ZSAkMTE5ZAIJDxYEHxAFiAFwb3B1cCgnQSBzYXZpbmcgYmFzZWQgb24gb3VyIHN0YW5kYXJkIHJhdGUgb2YgJDY5L2RheS4gUXVvdGVkIHJhdGUgaW5jbHVkZXMgR1NULCB1bmxpbWl0ZWQga21zLCBhbmQgc3RhbmRhcmQgaW5zdXJhbmNlICgkMSw1MDAgZXhjZXNzKScpHxEFBmtpbGwoKWQCAg9kFgICAQ9kFgJmDxYCHwQFCTIwMDYtMjAwN2QCAw9kFgICAQ9kFgJmDxYCHwQFBm1hbnVhbGQCBA9kFgICAQ9kFgJmDxYCHwQFBjIwMDBjY2QCBQ9kFgRmD2QWAmYPFgQfEAWhAnBvcHVwKCc8Yj4yMCUgT2ZmIEZ1ZWw8L2I+PGJyIC8+PHA+V2Ugd2lsbCBzZWxsIHlvdSB5b3VyIGZpcnN0IHRhbmsgb2YgZnVlbCBhdCBhIDIwJSBkaXNjb3VudCB0byB0aGUgY3VycmVudCBwcmljZSBvZiBmdWVsIGF2YWlsYWJsZSBhdCB0aGUgY2xvc2VzdCBwZXRyb2wgc3RhdGlvbiB0byB5b3VyIHBpY2t1cCBkZXBvdC48L3A+PHA+V2hlbiB5b3UgcmV0dXJuIHRoZSB2ZWhpY2xlIHRoZXJlIGlzIG5vIG5lZWQgdG8gcmVmdWVsIGl0LiBXZSBkbyBub3QgcmVmdW5kIGZvciB1bnVzZWQgZnVlbC48L3A+JykfEQUGa2lsbCgpFgJmDw8WBB8KBR9+L2ltYWdlcy9vZmZlcnMvMjAtb2ZmLWZ1ZWwuZ2lmHwNnZGQCAg9kFgJmD2QWEGYPDxYCHwoFL2h0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3MvYWR1bHQuZ2lmZGQCAQ8WAh8EBQJ4NWQCAg8PFgQfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9jaGlsZC5naWYfA2hkZAIDDxYEHwQFAngwHwNoZAIEDw8WAh8KBThodHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL3N1aXRjYXNlX2xhcmdlLmdpZmRkAgUPFgIfBAUCeDJkAgYPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2Vfc21hbGwuZ2lmZGQCBw8WAh8EBQJ4MmQCBg9kFgICAQ9kFgJmDw8WAh8EBQlPTlJFUVVFU1RkZAIHD2QWAmYPZBYIAgEPDxYCHxMFBDEzMTFkZAIDDw8WAh8TBQQxMzExZGQCBQ8PFgIfEwUEMTMxMWRkAgcPDxYEHwoFGX4vZ3JhcGhpY3MvYnRuLXNlbGVjdC5wbmcfEwUEMTMxMWRkAgQPFgIfA2hkAgMPZBYCAgUPDxYEHw5oHw8FBDEzMDlkFgYCAg8WAh8DaGQCAw8WAh8SZRYQZg9kFgRmD2QWAmYPDxYCHwoFJH4vaW1hZ2VzL2NhcnMvZWNvbm9teXdhZ29uLXNtYWxsLmpwZ2RkAgEPZBYCZg8PFgQfBAUTZWNvbm9teSB3YWdvbiBhdXRvIB8TBQQxMzA5ZGQCAQ9kFgQCAQ9kFgJmDxYCHwQFEE5pc3NhbiBXaW5ncm9hZCBkAgIPZBYKAgEPDxYCHwQFAyQ1M2RkAgMPDxYCHwoFGn4vZ3JhcGhpY3MvcGVyZGF5X3N0YXIuZ2lmZGQCBQ8WAh8EBQZOWiQzNzNkAgcPFgIfBAUIc2F2ZSAkNDBkAgkPFgQfEAWIAXBvcHVwKCdBIHNhdmluZyBiYXNlZCBvbiBvdXIgc3RhbmRhcmQgcmF0ZSBvZiAkNTkvZGF5LiBRdW90ZWQgcmF0ZSBpbmNsdWRlcyBHU1QsIHVubGltaXRlZCBrbXMsIGFuZCBzdGFuZGFyZCBpbnN1cmFuY2UgKCQxLDUwMCBleGNlc3MpJykfEQUGa2lsbCgpZAICD2QWAgIBD2QWAmYPFgIfBAUJMjAwNC0yMDA3ZAIDD2QWAgIBD2QWAmYPFgIfBAUJYXV0b21hdGljZAIED2QWAgIBD2QWAmYPFgIfBAUGMTUwMGNjZAIFD2QWBGYPZBYCZg8WBB8QBV1wb3B1cCgnPGI+QWlyIENvbmRpdGlvbmluZzwvYj48YnIgLz5BbGwgQXBleCB2ZWhpY2xlcyBhcmUgYWlyIGNvbmRpdGlvbmVkIGZvciB5b3VyIGNvbWZvcnQuJykfEQUGa2lsbCgpFgJmDw8WBB8KBSR+L2ltYWdlcy9vZmZlcnMvYWlyLWNvbmRpdGlvbmluZy5naWYfA2dkZAICD2QWAmYPZBYQZg8PFgIfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9hZHVsdC5naWZkZAIBDxYCHwQFAng1ZAICDw8WBB8KBS9odHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL2NoaWxkLmdpZh8DaGRkAgMPFgQfBAUCeDAfA2hkAgQPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2VfbGFyZ2UuZ2lmZGQCBQ8WAh8EBQJ4M2QCBg8PFgIfCgU4aHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9zdWl0Y2FzZV9zbWFsbC5naWZkZAIHDxYCHwQFAngyZAIGD2QWAgIBD2QWAmYPDxYCHwQFCUFWQUlMQUJMRWRkAgcPZBYCZg9kFggCAQ8PFgIfEwUEMTMwOWRkAgMPDxYCHxMFBDEzMDlkZAIFDw8WAh8TBQQxMzA5ZGQCBw8PFgIfEwUEMTMwOWRkAgQPFgIfA2hkAgQPZBYCAgUPDxYEHw5oHw8FBDIxNjZkFgYCAg8WAh8DaGQCAw8WAh8SZRYQZg9kFgRmD2QWAmYPDxYCHwoFK34vaW1hZ2VzL2NhcnMvcHJlbWl1bWhhdGNobWlkc2l6ZS1zbWFsbC5qcGdkZAIBD2QWAmYPDxYEHwQFFXByZW1pdW0gaGF0Y2ggbWlkc2l6ZR8TBQQyMTY2ZGQCAQ9kFgQCAQ9kFgJmDxYCHwQFFFRveW90YSBDb3JvbGxhIEhhdGNoZAICD2QWCgIBDw8WAh8EBQMkNTdkZAIDDw8WAh8KBRp+L2dyYXBoaWNzL3BlcmRheV9zdGFyLmdpZmRkAgUPFgIfBAUGTlokMzk5ZAIHDxYCHwQFCXNhdmUgJDE1NGQCCQ8WBB8QBYgBcG9wdXAoJ0Egc2F2aW5nIGJhc2VkIG9uIG91ciBzdGFuZGFyZCByYXRlIG9mICQ3OS9kYXkuIFF1b3RlZCByYXRlIGluY2x1ZGVzIEdTVCwgdW5saW1pdGVkIGttcywgYW5kIHN0YW5kYXJkIGluc3VyYW5jZSAoJDEsNTAwIGV4Y2VzcyknKR8RBQZraWxsKClkAgIPZBYCAgEPZBYCZg8WAh8EBQQyMDEwZAIDD2QWAgIBD2QWAmYPFgIfBAUGbWFudWFsZAIED2QWAgIBD2QWAmYPFgIfBAUGMTgwMGNjZAIFD2QWBGYPZBYCZg8WBB8QBaECcG9wdXAoJzxiPjIwJSBPZmYgRnVlbDwvYj48YnIgLz48cD5XZSB3aWxsIHNlbGwgeW91IHlvdXIgZmlyc3QgdGFuayBvZiBmdWVsIGF0IGEgMjAlIGRpc2NvdW50IHRvIHRoZSBjdXJyZW50IHByaWNlIG9mIGZ1ZWwgYXZhaWxhYmxlIGF0IHRoZSBjbG9zZXN0IHBldHJvbCBzdGF0aW9uIHRvIHlvdXIgcGlja3VwIGRlcG90LjwvcD48cD5XaGVuIHlvdSByZXR1cm4gdGhlIHZlaGljbGUgdGhlcmUgaXMgbm8gbmVlZCB0byByZWZ1ZWwgaXQuIFdlIGRvIG5vdCByZWZ1bmQgZm9yIHVudXNlZCBmdWVsLjwvcD4nKR8RBQZraWxsKCkWAmYPDxYEHwoFH34vaW1hZ2VzL29mZmVycy8yMC1vZmYtZnVlbC5naWYfA2dkZAICD2QWAmYPZBYQZg8PFgIfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9hZHVsdC5naWZkZAIBDxYCHwQFAng1ZAICDw8WBB8KBS9odHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL2NoaWxkLmdpZh8DaGRkAgMPFgQfBAUCeDAfA2hkAgQPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2VfbGFyZ2UuZ2lmZGQCBQ8WAh8EBQJ4MWQCBg8PFgIfCgU4aHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9zdWl0Y2FzZV9zbWFsbC5naWZkZAIHDxYCHwQFAngzZAIGD2QWAgIBD2QWAmYPDxYCHwQFCUFWQUlMQUJMRWRkAgcPZBYCZg9kFggCAQ8PFgIfEwUEMjE2NmRkAgMPDxYCHxMFBDIxNjZkZAIFDw8WAh8TBQQyMTY2ZGQCBw8PFgIfEwUEMjE2NmRkAgQPFgIfA2hkAgUPZBYCAgUPDxYEHw5oHw8FBDI1NTZkFgYCAg8WAh8DaGQCAw8WAh8SZRYQZg9kFgRmD2QWAmYPDxYCHwoFJn4vaW1hZ2VzL2NhcnMvcHJlbWl1bWNvbXBhY3Qtc21hbGwuanBnZGQCAQ9kFgJmDw8WBB8EBQ9wcmVtaXVtIGNvbXBhY3QfEwUEMjU1NmRkAgEPZBYEAgEPZBYCZg8WAh8EBQxUb3lvdGEgWWFyaXNkAgIPZBYKAgEPDxYCHwQFAyQ1OWRkAgMPDxYCHwoFGn4vZ3JhcGhpY3MvcGVyZGF5X3N0YXIuZ2lmZGQCBQ8WAh8EBQZOWiQ0MTNkAgcPFgIfBAUIc2F2ZSAkNzBkAgkPFgQfEAWIAXBvcHVwKCdBIHNhdmluZyBiYXNlZCBvbiBvdXIgc3RhbmRhcmQgcmF0ZSBvZiAkNjkvZGF5LiBRdW90ZWQgcmF0ZSBpbmNsdWRlcyBHU1QsIHVubGltaXRlZCBrbXMsIGFuZCBzdGFuZGFyZCBpbnN1cmFuY2UgKCQxLDUwMCBleGNlc3MpJykfEQUGa2lsbCgpZAICD2QWAgIBD2QWAmYPFgIfBAUJMjAxMS0yMDEyZAIDD2QWAgIBD2QWAmYPFgIfBAUJYXV0b21hdGljZAIED2QWAgIBD2QWAmYPFgIfBAUGMTMwMGNjZAIFD2QWBGYPZBYCZg8WBB8QBXBwb3B1cCgnPGI+U2VsbGluZyBPdXQgRmFzdDwvYj48YnIgLz5UaGlzIHJlbnRhbCBjYXIgaXMgc2VsbGluZyBmYXN0LCBwbGVhc2UgYm9vayBzb29uIHRvIGF2b2lkIGRpc2FwcG9pbnRtZW50LicpHxEFBmtpbGwoKRYCZg8PFgQfCgUefi9pbWFnZXMvb2ZmZXJzL3NlbGxpbmdvdXQuZ2lmHwNnZGQCAg9kFgJmD2QWEGYPDxYCHwoFL2h0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3MvYWR1bHQuZ2lmZGQCAQ8WAh8EBQJ4NWQCAg8PFgQfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9jaGlsZC5naWYfA2hkZAIDDxYEHwQFAngwHwNoZAIEDw8WAh8KBThodHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL3N1aXRjYXNlX2xhcmdlLmdpZmRkAgUPFgIfBAUCeDFkAgYPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2Vfc21hbGwuZ2lmZGQCBw8WAh8EBQJ4MmQCBg9kFgICAQ9kFgJmDw8WAh8EBQlBVkFJTEFCTEVkZAIHD2QWAmYPZBYIAgEPDxYCHxMFBDI1NTZkZAIDDw8WAh8TBQQyNTU2ZGQCBQ8PFgIfEwUEMjU1NmRkAgcPDxYCHxMFBDI1NTZkZAIEDxYCHwNoZAIGD2QWAgIFDw8WBB8OaB8PBQQxMTY2ZBYGAgIPFgIfA2hkAgMPFgIfEmUWEGYPZBYEZg9kFgJmDw8WAh8KBSd+L2ltYWdlcy9jYXJzL2Vjb25vbXlmdWxsc2l6ZS1zbWFsbC5qcGdkZAIBD2QWAmYPDxYEHwQFB3RvdXJpbmcfEwUEMTE2NmRkAgEPZBYEAgEPZBYCZg8WAh8EBQxUb3lvdGEgQ2FtcnlkAgIPZBYKAgEPDxYCHwQFAyQ2MmRkAgMPDxYCHwoFGn4vZ3JhcGhpY3MvcGVyZGF5X3N0YXIuZ2lmZGQCBQ8WAh8EBQZOWiQ0MzRkAgcPFgIfBAUIc2F2ZSAkNDlkAgkPFgQfEAWIAXBvcHVwKCdBIHNhdmluZyBiYXNlZCBvbiBvdXIgc3RhbmRhcmQgcmF0ZSBvZiAkNjkvZGF5LiBRdW90ZWQgcmF0ZSBpbmNsdWRlcyBHU1QsIHVubGltaXRlZCBrbXMsIGFuZCBzdGFuZGFyZCBpbnN1cmFuY2UgKCQxLDUwMCBleGNlc3MpJykfEQUGa2lsbCgpZAICD2QWAgIBD2QWAmYPFgIfBAUEMjAwNmQCAw9kFgICAQ9kFgJmDxYCHwQFCWF1dG9tYXRpY2QCBA9kFgICAQ9kFgJmDxYCHwQFBjI0MDBjY2QCBQ9kFgRmD2QWAmYPFgQfEAWhAnBvcHVwKCc8Yj4yMCUgT2ZmIEZ1ZWw8L2I+PGJyIC8+PHA+V2Ugd2lsbCBzZWxsIHlvdSB5b3VyIGZpcnN0IHRhbmsgb2YgZnVlbCBhdCBhIDIwJSBkaXNjb3VudCB0byB0aGUgY3VycmVudCBwcmljZSBvZiBmdWVsIGF2YWlsYWJsZSBhdCB0aGUgY2xvc2VzdCBwZXRyb2wgc3RhdGlvbiB0byB5b3VyIHBpY2t1cCBkZXBvdC48L3A+PHA+V2hlbiB5b3UgcmV0dXJuIHRoZSB2ZWhpY2xlIHRoZXJlIGlzIG5vIG5lZWQgdG8gcmVmdWVsIGl0LiBXZSBkbyBub3QgcmVmdW5kIGZvciB1bnVzZWQgZnVlbC48L3A+JykfEQUGa2lsbCgpFgJmDw8WBB8KBR9+L2ltYWdlcy9vZmZlcnMvMjAtb2ZmLWZ1ZWwuZ2lmHwNnZGQCAg9kFgJmD2QWEGYPDxYCHwoFL2h0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3MvYWR1bHQuZ2lmZGQCAQ8WAh8EBQJ4NWQCAg8PFgQfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9jaGlsZC5naWYfA2hkZAIDDxYEHwQFAngwHwNoZAIEDw8WAh8KBThodHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL3N1aXRjYXNlX2xhcmdlLmdpZmRkAgUPFgIfBAUCeDJkAgYPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2Vfc21hbGwuZ2lmZGQCBw8WAh8EBQJ4M2QCBg9kFgICAQ9kFgJmDw8WAh8EBQlBVkFJTEFCTEVkZAIHD2QWAmYPZBYIAgEPDxYCHxMFBDExNjZkZAIDDw8WAh8TBQQxMTY2ZGQCBQ8PFgIfEwUEMTE2NmRkAgcPDxYCHxMFBDExNjZkZAIEDxYCHwNoZAIHD2QWAgIFDw8WBB8OaB8PBQQxMzEwZBYGAgIPFgIfA2hkAgMPFgIfEmUWEGYPZBYEZg9kFgJmDw8WAh8KBSJ+L2ltYWdlcy9jYXJzL3N1cGVyaGF0Y2gtc21hbGwuanBnZGQCAQ9kFgJmDw8WBB8EBRBzdXBlciBoYXRjaCBhdXRvHxMFBDEzMTBkZAIBD2QWBAIBD2QWAmYPFgIfBAUKRm9yZCBGb2N1c2QCAg9kFgoCAQ8PFgIfBAUDJDY4ZGQCAw8PFgIfCgUafi9ncmFwaGljcy9wZXJkYXlfc3Rhci5naWZkZAIFDxYCHwQFBk5aJDQ3NmQCBw8WAh8EBQdzYXZlICQ3ZAIJDxYEHxAFiAFwb3B1cCgnQSBzYXZpbmcgYmFzZWQgb24gb3VyIHN0YW5kYXJkIHJhdGUgb2YgJDY5L2RheS4gUXVvdGVkIHJhdGUgaW5jbHVkZXMgR1NULCB1bmxpbWl0ZWQga21zLCBhbmQgc3RhbmRhcmQgaW5zdXJhbmNlICgkMSw1MDAgZXhjZXNzKScpHxEFBmtpbGwoKWQCAg9kFgICAQ9kFgJmDxYCHwQFBDIwMDdkAgMPZBYCAgEPZBYCZg8WAh8EBQlhdXRvbWF0aWNkAgQPZBYCAgEPZBYCZg8WAh8EBQYyMDAwY2NkAgUPZBYEZg9kFgJmDxYEHxAFoQJwb3B1cCgnPGI+MjAlIE9mZiBGdWVsPC9iPjxiciAvPjxwPldlIHdpbGwgc2VsbCB5b3UgeW91ciBmaXJzdCB0YW5rIG9mIGZ1ZWwgYXQgYSAyMCUgZGlzY291bnQgdG8gdGhlIGN1cnJlbnQgcHJpY2Ugb2YgZnVlbCBhdmFpbGFibGUgYXQgdGhlIGNsb3Nlc3QgcGV0cm9sIHN0YXRpb24gdG8geW91ciBwaWNrdXAgZGVwb3QuPC9wPjxwPldoZW4geW91IHJldHVybiB0aGUgdmVoaWNsZSB0aGVyZSBpcyBubyBuZWVkIHRvIHJlZnVlbCBpdC4gV2UgZG8gbm90IHJlZnVuZCBmb3IgdW51c2VkIGZ1ZWwuPC9wPicpHxEFBmtpbGwoKRYCZg8PFgQfCgUffi9pbWFnZXMvb2ZmZXJzLzIwLW9mZi1mdWVsLmdpZh8DZ2RkAgIPZBYCZg9kFhBmDw8WAh8KBS9odHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL2FkdWx0LmdpZmRkAgEPFgIfBAUCeDVkAgIPDxYEHwoFL2h0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3MvY2hpbGQuZ2lmHwNoZGQCAw8WBB8EBQJ4MB8DaGQCBA8PFgIfCgU4aHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9zdWl0Y2FzZV9sYXJnZS5naWZkZAIFDxYCHwQFAngyZAIGDw8WAh8KBThodHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL3N1aXRjYXNlX3NtYWxsLmdpZmRkAgcPFgIfBAUCeDJkAgYPZBYCAgEPZBYCZg8PFgIfBAUJT05SRVFVRVNUZGQCBw9kFgJmD2QWCAIBDw8WAh8TBQQxMzEwZGQCAw8PFgIfEwUEMTMxMGRkAgUPDxYCHxMFBDEzMTBkZAIHDw8WBB8KBRl+L2dyYXBoaWNzL2J0bi1zZWxlY3QucG5nHxMFBDEzMTBkZAIEDxYCHwNoZAIID2QWBAIDDw8WBB8EBWE8aDIgY2xhc3M9J2NhcnNPdGhlckgyJz5vdGhlciB2ZWhpY2xlczwvaDI+PGRpdiBjbGFzcz0nbGlzdGluZ0RpdmlkZXInIHN0eWxlPSd3aWR0aDoxMDAlOyc+PC9kaXY+HwNnZGQCBQ8PFgQfDmgfDwUEMTE3MWQWBgICDxYCHwNoZAIDDxYCHxJlFhBmD2QWBGYPZBYCZg8PFgIfCgUnfi9pbWFnZXMvY2Fycy9yb2FkdHJpcHNwZWNpYWwtc21hbGwuanBnZGQCAQ9kFgJmDw8WBB8EBRByb2FkdHJpcCBzcGVjaWFsHxMFBDExNzFkZAIBD2QWBAIBD2QWAmYPFgIfBAUMNC1Eb29yIFNlZGFuZAICD2QWCgIBDw8WAh8EBQMkNDRkZAIDDw8WAh8KBRp+L2dyYXBoaWNzL3BlcmRheV9zdGFyLmdpZmRkAgUPFgIfBAUGTlokMzA4ZAIHDxYCHwQFB3NhdmUgJDdkAgkPFgQfEAWIAXBvcHVwKCdBIHNhdmluZyBiYXNlZCBvbiBvdXIgc3RhbmRhcmQgcmF0ZSBvZiAkNDUvZGF5LiBRdW90ZWQgcmF0ZSBpbmNsdWRlcyBHU1QsIHVubGltaXRlZCBrbXMsIGFuZCBzdGFuZGFyZCBpbnN1cmFuY2UgKCQxLDUwMCBleGNlc3MpJykfEQUGa2lsbCgpZAICD2QWAgIBD2QWAmYPFgIfBAUJMjAwMC0yMDAyZAIDD2QWAgIBD2QWAmYPFgIfBAULbm9uc3BlY2lmaWNkAgQPZBYCAgEPZBYCZg8WAh8EBQkxNTAwLTE4MDBkAgUPZBYEZg9kFgJmDxYEHxAFcHBvcHVwKCc8Yj5TZWxsaW5nIE91dCBGYXN0PC9iPjxiciAvPlRoaXMgcmVudGFsIGNhciBpcyBzZWxsaW5nIGZhc3QsIHBsZWFzZSBib29rIHNvb24gdG8gYXZvaWQgZGlzYXBwb2ludG1lbnQuJykfEQUGa2lsbCgpFgJmDw8WBB8KBR5+L2ltYWdlcy9vZmZlcnMvc2VsbGluZ291dC5naWYfA2dkZAICD2QWAmYPZBYQZg8PFgIfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9hZHVsdC5naWZkZAIBDxYCHwQFAng1ZAICDw8WBB8KBS9odHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL2NoaWxkLmdpZh8DaGRkAgMPFgQfBAUCeDAfA2hkAgQPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2VfbGFyZ2UuZ2lmZGQCBQ8WAh8EBQJ4MmQCBg8PFgIfCgU4aHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9zdWl0Y2FzZV9zbWFsbC5naWZkZAIHDxYCHwQFAngyZAIGD2QWAgIBD2QWAmYPDxYCHwQFCU9OUkVRVUVTVGRkAgcPZBYCZg9kFggCAQ8PFgIfEwUEMTE3MWRkAgMPDxYCHxMFBDExNzFkZAIFDw8WAh8TBQQxMTcxZGQCBw8PFgQfCgUZfi9ncmFwaGljcy9idG4tc2VsZWN0LnBuZx8TBQQxMTcxZGQCBA8WAh8DaGQCCQ9kFgICBQ8PFgQfDmgfDwUEMTE1OWQWBgICDxYCHwNoZAIDDxYCHxJlFhBmD2QWBGYPZBYCZg8PFgIfCgUkfi9pbWFnZXMvY2Fycy9lY29ub215aGF0Y2gtc21hbGwuanBnZGQCAQ9kFgJmDw8WBB8EBQ1lY29ub215IGhhdGNoHxMFBDExNTlkZAIBD2QWBAIBD2QWAmYPFgIfBAUWTWF6ZGEgRGVtaW8gb3Igc2ltaWxhcmQCAg9kFgoCAQ8PFgIfBAUDJDQ2ZGQCAw8PFgIfCgUafi9ncmFwaGljcy9wZXJkYXlfc3Rhci5naWZkZAIFDxYCHwQFBk5aJDMyMmQCBw8WAh8EZWQCCQ8WBh8QBYgBcG9wdXAoJ0Egc2F2aW5nIGJhc2VkIG9uIG91ciBzdGFuZGFyZCByYXRlIG9mICQ0NS9kYXkuIFF1b3RlZCByYXRlIGluY2x1ZGVzIEdTVCwgdW5saW1pdGVkIGttcywgYW5kIHN0YW5kYXJkIGluc3VyYW5jZSAoJDEsNTAwIGV4Y2VzcyknKR8RBQZraWxsKCkfA2hkAgIPZBYCAgEPZBYCZg8WAh8EBQkyMDA1LTIwMDdkAgMPZBYCAgEPZBYCZg8WAh8EBQtub25zcGVjaWZpY2QCBA9kFgICAQ9kFgJmDxYCHwQFBjEzMDBjY2QCBQ9kFgRmD2QWAmYPFgQfEAWhAnBvcHVwKCc8Yj4yMCUgT2ZmIEZ1ZWw8L2I+PGJyIC8+PHA+V2Ugd2lsbCBzZWxsIHlvdSB5b3VyIGZpcnN0IHRhbmsgb2YgZnVlbCBhdCBhIDIwJSBkaXNjb3VudCB0byB0aGUgY3VycmVudCBwcmljZSBvZiBmdWVsIGF2YWlsYWJsZSBhdCB0aGUgY2xvc2VzdCBwZXRyb2wgc3RhdGlvbiB0byB5b3VyIHBpY2t1cCBkZXBvdC48L3A+PHA+V2hlbiB5b3UgcmV0dXJuIHRoZSB2ZWhpY2xlIHRoZXJlIGlzIG5vIG5lZWQgdG8gcmVmdWVsIGl0LiBXZSBkbyBub3QgcmVmdW5kIGZvciB1bnVzZWQgZnVlbC48L3A+JykfEQUGa2lsbCgpFgJmDw8WBB8KBR9+L2ltYWdlcy9vZmZlcnMvMjAtb2ZmLWZ1ZWwuZ2lmHwNnZGQCAg9kFgJmD2QWEGYPDxYCHwoFL2h0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3MvYWR1bHQuZ2lmZGQCAQ8WAh8EBQJ4NWQCAg8PFgQfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9jaGlsZC5naWYfA2hkZAIDDxYEHwQFAngwHwNoZAIEDw8WAh8KBThodHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL3N1aXRjYXNlX2xhcmdlLmdpZmRkAgUPFgIfBAUCeDFkAgYPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2Vfc21hbGwuZ2lmZGQCBw8WAh8EBQJ4MmQCBg9kFgICAQ9kFgJmDw8WAh8EBQlBVkFJTEFCTEVkZAIHD2QWAmYPZBYIAgEPDxYCHxMFBDExNTlkZAIDDw8WAh8TBQQxMTU5ZGQCBQ8PFgIfEwUEMTE1OWRkAgcPDxYCHxMFBDExNTlkZAIEDxYCHwNoZAIKD2QWAgIFDw8WBB8OaB8PBQQxMTY3ZBYGAgIPFgIfA2hkAgMPFgIfEmUWEGYPZBYEZg9kFgJmDw8WAh8KBSd+L2ltYWdlcy9jYXJzL3ByZW1pdW1mdWxsc2l6ZS1zbWFsbC5qcGdkZAIBD2QWAmYPDxYEHwQFF3ByZW1pdW0gc2VkYW4gZnVsbCBzaXplHxMFBDExNjdkZAIBD2QWBAIBD2QWAmYPFgIfBAUMVG95b3RhIENhbXJ5ZAICD2QWCgIBDw8WAh8EBQMkNzRkZAIDDw8WAh8KBRp+L2dyYXBoaWNzL3BlcmRheV9zdGFyLmdpZmRkAgUPFgIfBAUGTlokNTE4ZAIHDxYCHwQFCXNhdmUgJDEwNWQCCQ8WBB8QBYgBcG9wdXAoJ0Egc2F2aW5nIGJhc2VkIG9uIG91ciBzdGFuZGFyZCByYXRlIG9mICQ4OS9kYXkuIFF1b3RlZCByYXRlIGluY2x1ZGVzIEdTVCwgdW5saW1pdGVkIGttcywgYW5kIHN0YW5kYXJkIGluc3VyYW5jZSAoJDEsNTAwIGV4Y2VzcyknKR8RBQZraWxsKClkAgIPZBYCAgEPZBYCZg8WAh8EBQkyMDA5LTIwMTBkAgMPZBYCAgEPZBYCZg8WAh8EBQlhdXRvbWF0aWNkAgQPZBYCAgEPZBYCZg8WAh8EBQYyNDAwY2NkAgUPZBYEZg9kFgJmDxYEHxAFcHBvcHVwKCc8Yj5TZWxsaW5nIE91dCBGYXN0PC9iPjxiciAvPlRoaXMgcmVudGFsIGNhciBpcyBzZWxsaW5nIGZhc3QsIHBsZWFzZSBib29rIHNvb24gdG8gYXZvaWQgZGlzYXBwb2ludG1lbnQuJykfEQUGa2lsbCgpFgJmDw8WBB8KBR5+L2ltYWdlcy9vZmZlcnMvc2VsbGluZ291dC5naWYfA2dkZAICD2QWAmYPZBYQZg8PFgIfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9hZHVsdC5naWZkZAIBDxYCHwQFAng1ZAICDw8WBB8KBS9odHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL2NoaWxkLmdpZh8DaGRkAgMPFgQfBAUCeDAfA2hkAgQPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2VfbGFyZ2UuZ2lmZGQCBQ8WAh8EBQJ4M2QCBg8PFgIfCgU4aHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9zdWl0Y2FzZV9zbWFsbC5naWZkZAIHDxYCHwQFAngyZAIGD2QWAgIBD2QWAmYPDxYCHwQFCUFWQUlMQUJMRWRkAgcPZBYCZg9kFggCAQ8PFgIfEwUEMTE2N2RkAgMPDxYCHxMFBDExNjdkZAIFDw8WAh8TBQQxMTY3ZGQCBw8PFgIfEwUEMTE2N2RkAgQPFgIfA2hkAgsPZBYCAgUPDxYEHw5oHw8FBDE1MThkFgYCAg8WAh8DaGQCAw8WAh8SZRYQZg9kFgRmD2QWAmYPDxYCHwoFI34vaW1hZ2VzL2NhcnMvNHg0ZnVsbHNpemUtc21hbGwuanBnZGQCAQ9kFgJmDw8WBB8EBQ00WDQgZnVsbCBzaXplHxMFBDE1MThkZAIBD2QWBAIBD2QWAmYPFgIfBAUeVG95b3RhIFJBVjQgb3IgU3ViYXJ1IEZvcmVzdGVyZAICD2QWCgIBDw8WAh8EBQMkOTNkZAIDDw8WAh8KBRp+L2dyYXBoaWNzL3BlcmRheV9zdGFyLmdpZmRkAgUPFgIfBAUGTlokNjUxZAIHDxYCHwQFCXNhdmUgJDExMmQCCQ8WBB8QBYkBcG9wdXAoJ0Egc2F2aW5nIGJhc2VkIG9uIG91ciBzdGFuZGFyZCByYXRlIG9mICQxMDkvZGF5LiBRdW90ZWQgcmF0ZSBpbmNsdWRlcyBHU1QsIHVubGltaXRlZCBrbXMsIGFuZCBzdGFuZGFyZCBpbnN1cmFuY2UgKCQxLDUwMCBleGNlc3MpJykfEQUGa2lsbCgpZAICD2QWAgIBD2QWAmYPFgIfBAUJMjAwOS0yMDExZAIDD2QWAgIBD2QWAmYPFgIfBAUJYXV0b21hdGljZAIED2QWAgIBD2QWAmYPFgIfBAUGMjQwMGNjZAIFD2QWBGYPZBYCZg8WBB8QBXBwb3B1cCgnPGI+U2VsbGluZyBPdXQgRmFzdDwvYj48YnIgLz5UaGlzIHJlbnRhbCBjYXIgaXMgc2VsbGluZyBmYXN0LCBwbGVhc2UgYm9vayBzb29uIHRvIGF2b2lkIGRpc2FwcG9pbnRtZW50LicpHxEFBmtpbGwoKRYCZg8PFgQfCgUefi9pbWFnZXMvb2ZmZXJzL3NlbGxpbmdvdXQuZ2lmHwNnZGQCAg9kFgJmD2QWEGYPDxYCHwoFL2h0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3MvYWR1bHQuZ2lmZGQCAQ8WAh8EBQJ4NWQCAg8PFgQfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9jaGlsZC5naWYfA2hkZAIDDxYEHwQFAngwHwNoZAIEDw8WAh8KBThodHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL3N1aXRjYXNlX2xhcmdlLmdpZmRkAgUPFgIfBAUCeDRkAgYPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2Vfc21hbGwuZ2lmZGQCBw8WAh8EBQJ4M2QCBg9kFgICAQ9kFgJmDw8WAh8EBQlBVkFJTEFCTEVkZAIHD2QWAmYPZBYIAgEPDxYCHxMFBDE1MThkZAIDDw8WAh8TBQQxNTE4ZGQCBQ8PFgIfEwUEMTUxOGRkAgcPDxYCHxMFBDE1MThkZAIEDxYCHwNoZAIMD2QWAgIFDw8WBB8OaB8PBQQyMTYxZBYGAgIPFgIfA2hkAgMPFgIfEmUWEGYPZBYEZg9kFgJmDw8WAh8KBSR+L2ltYWdlcy9jYXJzLzR3ZHdhZ29uMjAxMS1zbWFsbC5qcGdkZAIBD2QWAmYPDxYEHwQFDjR3ZCB3YWdvbiAyMDExHxMFBDIxNjFkZAIBD2QWBAIBD2QWAmYPFgIfBAURU3ViYXJ1IExlZ2FjeSA0V0RkAgIPZBYKAgEPDxYCHwQFAyQ5OWRkAgMPDxYCHwoFGn4vZ3JhcGhpY3MvcGVyZGF5X3N0YXIuZ2lmZGQCBQ8WAh8EBQZOWiQ2OTNkAgcPFgIfBAUHc2F2ZSAkN2QCCQ8WBB8QBYkBcG9wdXAoJ0Egc2F2aW5nIGJhc2VkIG9uIG91ciBzdGFuZGFyZCByYXRlIG9mICQxMDAvZGF5LiBRdW90ZWQgcmF0ZSBpbmNsdWRlcyBHU1QsIHVubGltaXRlZCBrbXMsIGFuZCBzdGFuZGFyZCBpbnN1cmFuY2UgKCQxLDUwMCBleGNlc3MpJykfEQUGa2lsbCgpZAICD2QWAgIBD2QWAmYPFgIfBAUEMjAxMWQCAw9kFgICAQ9kFgJmDxYCHwQFCWF1dG9tYXRpY2QCBA9kFgICAQ9kFgJmDxYCHwQFBjI1MDBjY2QCBQ9kFgRmD2QWAmYPFgQfEAVNcG9wdXAoJzxiPkd1YXJhbnRlZWQgTW9kZWw8L2I+PGJyIC8+VGhpcyBtb2RlbCBvZiByZW50YWwgY2FyIGlzIGd1YXJhbnRlZWQuJykfEQUGa2lsbCgpFgJmDw8WBB8KBSR+L2ltYWdlcy9vZmZlcnMvZ3VhcmFudGVlZF9tb2RlbC5naWYfA2dkZAICD2QWAmYPZBYQZg8PFgIfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9hZHVsdC5naWZkZAIBDxYCHwQFAng1ZAICDw8WBB8KBS9odHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL2NoaWxkLmdpZh8DaGRkAgMPFgQfBAUCeDAfA2hkAgQPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2VfbGFyZ2UuZ2lmZGQCBQ8WAh8EBQJ4M2QCBg8PFgIfCgU4aHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9zdWl0Y2FzZV9zbWFsbC5naWZkZAIHDxYCHwQFAng0ZAIGD2QWAgIBD2QWAmYPDxYCHwQFCUFWQUlMQUJMRWRkAgcPZBYCZg9kFggCAQ8PFgIfEwUEMjE2MWRkAgMPDxYCHxMFBDIxNjFkZAIFDw8WAh8TBQQyMTYxZGQCBw8PFgIfEwUEMjE2MWRkAgQPFgIfA2hkAg0PZBYCAgUPDxYEHw5oHw8FBDExNjhkFgYCAg8WAh8DaGQCAw8WAh8SZRYQZg9kFgRmD2QWAmYPDxYCHwoFG34vaW1hZ2VzL2NhcnMvbXB2LXNtYWxsLmpwZ2RkAgEPZBYCZg8PFgQfBAUMbXB2IDggc2VhdGVyHxMFBDExNjhkZAIBD2QWBAIBD2QWAmYPFgIfBAUNVG95b3RhIFByZXZpYWQCAg9kFgoCAQ8PFgIfBAUEJDEwOWRkAgMPDxYCHwoFGn4vZ3JhcGhpY3MvcGVyZGF5X3N0YXIuZ2lmZGQCBQ8WAh8EBQZOWiQ3NjBkAgcPFgIfBAUHc2F2ZSAkM2QCCQ8WBB8QBYkBcG9wdXAoJ0Egc2F2aW5nIGJhc2VkIG9uIG91ciBzdGFuZGFyZCByYXRlIG9mICQxMDkvZGF5LiBRdW90ZWQgcmF0ZSBpbmNsdWRlcyBHU1QsIHVubGltaXRlZCBrbXMsIGFuZCBzdGFuZGFyZCBpbnN1cmFuY2UgKCQxLDUwMCBleGNlc3MpJykfEQUGa2lsbCgpZAICD2QWAgIBD2QWAmYPFgIfBAUJMjAwNy0yMDEwZAIDD2QWAgIBD2QWAmYPFgIfBAUJYXV0b21hdGljZAIED2QWAgIBD2QWAmYPFgIfBAUGMjQwMGNjZAIFD2QWBGYPZBYCZg8WBB8QBXBwb3B1cCgnPGI+U2VsbGluZyBPdXQgRmFzdDwvYj48YnIgLz5UaGlzIHJlbnRhbCBjYXIgaXMgc2VsbGluZyBmYXN0LCBwbGVhc2UgYm9vayBzb29uIHRvIGF2b2lkIGRpc2FwcG9pbnRtZW50LicpHxEFBmtpbGwoKRYCZg8PFgQfCgUefi9pbWFnZXMvb2ZmZXJzL3NlbGxpbmdvdXQuZ2lmHwNnZGQCAg9kFgJmD2QWEGYPDxYCHwoFL2h0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3MvYWR1bHQuZ2lmZGQCAQ8WAh8EBQJ4OGQCAg8PFgQfCgUvaHR0cDovL3d3dy5hcGV4cmVudGFscy5jby5uei9ncmFwaGljcy9jaGlsZC5naWYfA2hkZAIDDxYEHwQFAngwHwNoZAIEDw8WAh8KBThodHRwOi8vd3d3LmFwZXhyZW50YWxzLmNvLm56L2dyYXBoaWNzL3N1aXRjYXNlX2xhcmdlLmdpZmRkAgUPFgIfBAUCeDNkAgYPDxYCHwoFOGh0dHA6Ly93d3cuYXBleHJlbnRhbHMuY28ubnovZ3JhcGhpY3Mvc3VpdGNhc2Vfc21hbGwuZ2lmZGQCBw8WAh8EBQJ4MmQCBg9kFgICAQ9kFgJmDw8WAh8EBQlBVkFJTEFCTEVkZAIHD2QWAmYPZBYIAgEPDxYCHxMFBDExNjhkZAIDDw8WAh8TBQQxMTY4ZGQCBQ8PFgIfEwUEMTE2OGRkAgcPDxYCHxMFBDExNjhkZAIEDxYCHwNoZAIHD2QWAgIDDxYCHwQFZVNvcnJ5IHRoZXJlIGFyZSBubyB2ZWhpY2xlcyBhdmFpbGFibGUgdGhhdCBtZWV0IHlvdXIgcmVxdWVzdC4gUGxlYXNlIHRyeSBhbHRlcm5hdGl2ZSBkYXRlcyBhbmQgdGltZXMuZAIJD2QWAgIBD2QWAmYPZBYCAiwPD2QPEBYBZhYBFgIeDlBhcmFtZXRlclZhbHVlZBYBAgNkZAILDw8WAh8DZ2RkAgMPZBYCZg9kFgICAQ9kFgICAw9kFgJmD2QWAgIDD2QWAgIBD2QWAgIsDw9kDxAWAWYWARYCHxRkFgECA2RkAiAPD2QPEBYBZhYBFgQeDERlZmF1bHRWYWx1ZQUEY2Fycx8UZBYBAgNkZAIFDxYCHwQFUjxzY3JpcHQgc3JjPSJodHRwczovL3NjcmlwdC5vcGVudHJhY2tlci5uZXQvP3NpdGU9d3d3LmFwZXhyZW50YWxzLmNvLm56Ij48L3NjcmlwdD5kGAMFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxZCBR1jdGwwMCRhc2tBUXVlc3Rpb24kaW1nRW1haWxVcwU9Y3RsMDAkY3RybFNlYXJjaENyaXRlcmlhJGN0cmxJc2xhbmRTZWxlY3RvciRJbnRlcklzbGFuZEhpcmUkMAU9Y3RsMDAkY3RybFNlYXJjaENyaXRlcmlhJGN0cmxJc2xhbmRTZWxlY3RvciRJbnRlcklzbGFuZEhpcmUkMQU9Y3RsMDAkY3RybFNlYXJjaENyaXRlcmlhJGN0cmxJc2xhbmRTZWxlY3RvciRJbnRlcklzbGFuZEhpcmUkMQUhY3RsMDAkY3RybFNlYXJjaENyaXRlcmlhJGltZ0J0bkdPBUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDAkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDAkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDAwJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDAwJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDEkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDEkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDAxJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDAxJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDIkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDIkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDAyJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDAyJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDMkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDMkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDAzJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDAzJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDQkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDQkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA0JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA0JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDUkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDUkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA1JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA1JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDYkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDYkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA2JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA2JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDckY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDckY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA3JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA3JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDgkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDgkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA4JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA4JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDkkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMDkkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA5JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDA5JGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMTAkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMTAkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDEwJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDEwJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMTEkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMTEkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDExJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDExJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMTIkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMTIkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDEyJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDEyJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BUxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMTMkY3RybFZlaGljbGVJdGVtJGltZ2J0bkVtYWlsRnJpZW5kBUpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGRsVmVoaWNsZXMkY3RsMTMkY3RybFZlaGljbGVJdGVtJGltZ2J0blNhdmVRdW90ZQVJY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDEzJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Nb3JlSW5mbwVIY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRkbFZlaGljbGVzJGN0bDEzJGN0cmxWZWhpY2xlSXRlbSRpbWdidG5Cb29rTm93BTtjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGN0cmxWZWhpY2xlUXVvdGUkaW1nYnRuQmFja1RvTGlzdAU8Y3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRjdHJsVmVoaWNsZVF1b3RlJGltZ2J0bkVtYWlsRnJpZW5kBTpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGN0cmxWZWhpY2xlUXVvdGUkaW1nYnRuU2F2ZVF1b3RlBThjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJGN0cmxWZWhpY2xlUXVvdGUkaW1nYnRuQm9va05vdwUpY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRpbWdidG5TZW5kUXVvdGUFE2N0bDAwJG11bFF1aWNrTGlua3MPD2RmZAUMY3RsMDAkbXVsTmF2Dw9kZmQ7CN3trvdO3ddrhlykNzmTG1NL0w==',                'ctl00$askAQuestion$zzFirstWatermark_ClientState' => '',                'ctl00$askAQuestion$zzName' => '',                'ctl00$askAQuestion$zzzzEmailWatermark_ClientState' => '',
                    'ctl00$askAQuestion$zzEmail' => '',
                    'ctl00$askAQuestion$zzCompany' => 'apex car rentals',
                    'ctl00$askAQuestion$zzCommentWatermark_ClientState' => '',
                    'ctl00$askAQuestion$zzComment' => '',
                    'ctl00$ctrlSearchCriteria$ddVehicleCategory' => '8',
                    'ctl00$ctrlSearchCriteria$PickUpLocation$ctrlLocation' => "$locOne",
                    'ctl00$ctrlSearchCriteria$PickUpDate$ddlOpeningHour' => '8',
                    'ctl00$ctrlSearchCriteria$PickUpDate$ddlClosingHour' => '17',
                    'ctl00$ctrlSearchCriteria$PickUpDate$ddlDay' => "$puDateSplit[0]",
                    'ctl00$ctrlSearchCriteria$PickUpDate$ddlMonth' => "$puDateSplit[1]",
                    'ctl00$ctrlSearchCriteria$PickUpDate$ddlYear' => "$puDateSplit[2]",
                    'ctl00$ctrlSearchCriteria$PickUpDate$ddTime' => '10',
                    'ctl00$ctrlSearchCriteria$PickUpDate$dateValid' => "$pudate 10:00:00 a.m.",
                    'ctl00$ctrlSearchCriteria$PickUpDate$txtDate' => '',
                    'ctl00$ctrlSearchCriteria$ctrlIslandSelector$InterIslandHire$1' => 'on',
                    'ctl00$ctrlSearchCriteria$DropOffLocation$ctrlLocation' => "$locTwo",
                    'ctl00$ctrlSearchCriteria$DropOffDate$ddlOpeningHour' => '8',
                    'ctl00$ctrlSearchCriteria$DropOffDate$ddlClosingHour' => '17',
                    'ctl00$ctrlSearchCriteria$DropOffDate$ddlDay' => "$doDateSplit[0]",
                    'ctl00$ctrlSearchCriteria$DropOffDate$ddlMonth' => "$doDateSplit[1]",
                    'ctl00$ctrlSearchCriteria$DropOffDate$ddlYear' => "$doDateSplit[2]",
                    'ctl00$ctrlSearchCriteria$DropOffDate$ddTime' => '10',
                    'ctl00$ctrlSearchCriteria$DropOffDate$dateValid' => "$dodate 9:00:00 a.m.",
                    'ctl00$ctrlSearchCriteria$DropOffDate$txtDate' => '',
                    'ctl00$ctrlSearchCriteria$ddTransmission' => '0',
                    'ctl00$ctrlICallContent$CollapsiblePanelExtender1_ClientState' => 'true',
                    'ctl00$ContentPlaceHolder1$CollapsiblePanelExtender1_ClientState' => '',
                    'ctl00$ContentPlaceHolder1$txtEmailWatermark_ClientState' => '',
                    'ctl00$ContentPlaceHolder1$txtEmail' => '',
                    'ctl00$ctrlSearchCriteria$imgBtnGO.x' => '32',
                    'ctl00$ctrlSearchCriteria$imgBtnGO.y' => '12');
                
                $data = $this->scrapeSite($url, $postdata);
                $largeCarArray = array_merge($largeCarArray, @$this->ApexCars($data));
            break;
        }
		//echo "<pre>";
		//print_r($largeCarArray);
		//echo "</pre>";
        // Will need to do this once finished testing
        //$largeCarArray = json_decode('[{"company":"AceRentals","image":"https:\/\/www.acerentalcars.co.nz\/images\/cars\/premcomp_md.jpg","title":"Premium Compact","type":"Daihatsu Sirion","gearbox":"Manual","size":"x4","price":"NZ$39.00"},{"company":"AceRentals","image":"https:\/\/www.acerentalcars.co.nz\/images\/cars\/economy_md.jpg","title":"Economy","type":"Nissan Tiida","gearbox":"Automatic","size":"x4","price":"NZ$39.00"},{"company":"AceRentals","image":"https:\/\/www.acerentalcars.co.nz\/images\/cars\/tourist_md.jpg","title":"Tourist","type":"Nissan Bluebird or Similar","gearbox":"Manual or automatic","size":"x5","price":"NZ$48.00"},{"company":"AceRentals","image":"https:\/\/www.acerentalcars.co.nz\/images\/cars\/sw_md.jpg","title":"Station Wagon","type":"Nissan Primera or Similar","gearbox":"Manual or automatic","size":"x5","price":"NZ$65.00"},{"company":"AceRentals","image":"https:\/\/www.acerentalcars.co.nz\/images\/cars\/4wd_sw_md.jpg","title":"4WD Station Wagon","type":"Subaru Legacy or similar","gearbox":"Automatic","size":"x5","price":"NZ$85.00"},{"company":"AceRentals","image":"https:\/\/www.acerentalcars.co.nz\/images\/cars\/pm_md.jpg","title":"People Mover","type":"Toyota Previa, Lucida, Estima or Similar","gearbox":"Manual or automatic","size":"x7","price":"NZ$84.00"},{"company":"AceRentals","image":"https:\/\/www.acerentalcars.co.nz\/images\/cars\/10seater_md.jpg","title":"10 Seater","type":"Toyota HiAce Van, Ford Econovan or Similar","gearbox":"Manual","size":"x10","price":"NZ$121.00"}]');
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
			$image = trim($carXpath->query("div[@class='list_left']/div[@class='list_image']/img/@src")->item(0)->nodeValue);
			$title = trim($carXpath->query("div[@class='list_left']/div[@class='list_title']/h3/text()")->item(0)->nodeValue);
			$price = trim($carXpath->query("div[@class='list_right']/div[@class='list_price']/div[@class='price']/text()")->item(0)->nodeValue);
			$type = trim($carXpath->query("div[@class='list_left']/div[@class='list_title']/h2/text()")->item(0)->nodeValue);
			$gearbox = $carXpath->query("div[@class='list_right']/div[@class='list_features']/div[@class='feature'][1]/text()[contains(.,'Manual') or contains(.,'Automatic')]");
			$gearbox = $gearbox->length ? trim($gearbox->item(0)->nodeValue) : "N/A";
			$size = $carXpath->query("div[@class='list_right']/div[@class='list_icons']/div[@class='icon adult']");
			$size = $size->length;
			
            $results[] = array(
            'company' => "Omega",
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
			
			$image = trim($carXpath->query("//img/@src")->item(0)->nodeValue);
			
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
        
        $site = $xpath->query("//div[@id='containerFooter']/div[@id='containerMain']/form[@id='aspnetForm']/div[@id='contentMain']/div[3]/div[2]/div[@id='ctl00_ContentPlaceHolder1_upCars']");
        
        foreach ( $site as $item ) {
            $tempDom->appendChild($tempDom->importNode($item,true));
        }
        $tempDom->saveHTML();
        $carsXpath = new DOMXPath($tempDom);
        $results = array();

        $cars = $carsXpath->query("table[@id='ctl00_ContentPlaceHolder1_dlVehicles']/tr/td");
        
        foreach ($cars as $car) {
            $newDom = new DOMDocument;
            $newDom->appendChild($newDom->importNode($car,true));
            $carXpath = new DOMXPath( $newDom );

            $image = "http://www.apexrentals.co.nz/".trim($carXpath->query("table/tr[1]/td[1]/img/@src")->item(0)->nodeValue);
            $title = trim($carXpath->query("table/tr[1]/td[2]/a/text()")->item(0)->nodeValue);
            $type =  trim($carXpath->query("table/tr[2]/td[2]/text()")->item(0)->nodeValue);
            $gearbox =  trim($carXpath->query("table/tr[4]/td[2]/text()")->item(0)->nodeValue);
            $size =  trim($carXpath->query("table/tr[6]/td[3]/text()[1]")->item(0)->nodeValue);
            $price =  trim($carXpath->query("table/tr[2]/td[3]/span/b/text()")->item(0)->nodeValue);

            $results[] = array(
				'company' => "Apex",
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
}
?>