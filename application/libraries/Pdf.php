<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
    public $title;
    public function setTitle($title)
    {
        $this->title = $title;
    }
	function __construct()
	{
		parent::__construct();
        
	}
	public function Header (){                       
		$this->SetFont('times', '', 14);
		$title = utf8_encode($this->title);
		$subtitle = utf8_encode('sub title');
		$this->SetHeaderMargin(20);  
//		$this->Line(15,23,405,23);
        $this->Cell(0, 15, $title, 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	public function Footer() {
		$this->SetFont('times', '', 8);
		$this-> Cell (0, 5, 'Generated On: '.date('d-m-Y').' Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}   

	public static function makeHTML ($json){
		$json = json_decode($json,true);
		
		$html = '<table border="0.5" cellspacing="0" cellpadding="4">';
		foreach($json as $key)
		{
			$html .= '<tr>';
			foreach($key as $each)
		          $html .= '<td>'.$each.'</td>';
			$html .= '</tr>';
		}   
		$html .= '</table>';
		return $html;           
	}   
}


