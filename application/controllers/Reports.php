<?php
   defined('BASEPATH') OR exit('No direct script access allowed');

   class Reports extends CI_Controller {

      /**
      * Index Page for this controller.
      *
      * Maps to the following URL
      * 		http://example.com/index.php/welcome
      *	- or -
      * 		http://example.com/index.php/welcome/index
      *	- or -
      * Since this controller is set as the default controller in
      * config/routes.php, it's displayed at http://example.com/
      *
      * So any other public methods not prefixed with an underscore will
      * map to /index.php/welcome/<method_name>
      * @see http://codeigniter.com/user_guide/general/urls.html
      */

      private $messTypes= array("JUNIOR MESS","SENIOR VEG MESS","SENIOR NON VEG MESS","GIRLS MESS");

      public function __construct()
      {
         parent::__construct();

         $this->load->model('reports_model');
         $this->load->model('mess_model');
         $this->load->helper('form');
         $this->load->helper('url');
         $this->load->library('session');
         $this->load->library('form_validation');
         $this->load->helper('date');

         $this->load->library('ion_auth');
         $this->load->library('pdf');


      }

      public function printReport ($title="",$mess,$from,$to)
      {
         set_time_limit(0);

         $pdf = new Pdf("P", PDF_UNIT, "A4",true, 'UTF-8', false);


         // set header and footer fonts
         // set margins
         $pdf->setTitle(strtoupper($title));
         $pdf->Header();
         $pdf->Footer();
         $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT,true);
         $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

         //$pdf->SetMargins (15, 27, 15, true);

         $pdf->SetFont('times', '', 16);
         $pdf->SetAutoPageBreak(TRUE,50);
         $pdf->AddPage();


         $pdf->SetFont('times', '', 14);
         if (strpos($title,'Bill') !== false) {
            $text = "Bill for ". $mess;
         }
         else if (strpos($title,'Consumption') !== false) {
            $text = "Consumption of ". $mess;
         }
         else if (strpos($title,'Returns') !== false) {
            $text = "Item returns report of ". $mess;
         }
         else if (strpos($title,'Details') !== false) {
            $text = "Details are given below";
         }



         $pdf->Ln();
         if($from != "undefined")
         {
            if($from == $to)
            $text .= " on the date ".$from;
            else
            $text .= " during the period from ".$from." to ".$to;
         }

         $pdf->Cell(0, 0, $text, 0, 0, 'C');
         $pdf->Ln();


         $pdf->SetFont('times', '', 12);
         $html = "";
         //create html
         $html .= '<html><head><title>Report</title>';

               $html .= '</head><body >';
               $base_path = base_url();

               $html .= '<style>table,tr,th{border: 1px solid black;}
                  tr[name=no_border],th[name=no_border]{border: 0px solid black;}</style>';
               $html .= $_POST['toSend'];
               $html .= ('</body></html>');

         $pdf->writeHTML($html, false, false, false, false, '');

         $pdf->Ln();
         $pdf->Ln();
         $pdf->Ln();
         $pdf->Ln();
         $pdf->Ln();
         $html = '<table><tr><th>Store Manager</th><th>Deputy Warden</th><th>Hostel Warden</th></tr></table>';
         $pdf->writeHTML($html, false, false, false, false, '');


         //	$pdf->Output('C:\xampp\htdocs\cegMessStore\reports\report.pdf', 'FD');  //save pdf
         $pdf->Output('/tmp/report.pdf', 'FD');  //save pdf
         //		$pdf->Output('file.pdf', 'I'); // show pdf

         return true;
      }

      public function printAbstract ($title="",$vendorName,$total,$startDate,$endDate)
      {
         set_time_limit(0);

         $pdf = new Pdf("P", PDF_UNIT, "A4",true, 'UTF-8', false);

         $date = date('d-m-Y');
         $filename = $vendorName."_".$startDate."_".$endDate."_".$date;

         // set header and footer fonts
         // set margins
         $pdf->setTitle('');
         $pdf->Header();
         $pdf->Footer();
         $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT,true);
         $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

         //$pdf->SetMargins (15, 27, 15, true);

         $pdf->SetFont('times', '', 14);
         $pdf->SetAutoPageBreak(TRUE,50);
         $pdf->AddPage();


         $pdf->Cell(0, 0, 'ENGINEERING COLLEGE HOSTELS', 0, 0, 'C');
         $pdf->Ln();

         $pdf->SetFont('times', '', 12);
         $pdf->Cell(0, 0, 'COLLEGE OF ENGINEERING, GUINDY', 0, 0, 'C');
         $pdf->Ln();
         $pdf->Cell(0, 0, 'ANNA UNIVERSITY, CHENNAI-25', 0, 0, 'C');
         $pdf->Ln();

         $pdf->SetFont('times', '', 14);
         $pdf->Cell(0, 0, 'ABSTRACT OF SUPPLIER\'S BILL', 0, 0, 'C');


         $pdf->Ln();
         $pdf->Ln();



         $pdf->SetFont('times', '', 14);
         $text = "BILL RECEIVED FROM ".$vendorName." FOR THE SUPPLIES MADE";

         $pdf->Cell(0, 0, $text, 0, 0, 'L');
         if($startDate == $endDate)
         $text = "ON ".$startDate;
         else
         $text = "BETWEEN ".$startDate." AND ".$endDate;


         $pdf->Ln();
         $pdf->Cell(0, 0, $text, 0, 0, 'L');

         $pdf->SetFont('times', '', 12);

         $html = "";
         //create html
         $html .= '<html><head><title>Report</title>';
               $html .= '</head><body >';
               $base_path = base_url();

               $html .= '<style>tr,th{border: 1px solid black;}</style>';
               $html .= $_POST['toSend'];
               $html .= ('</body></html>');

         $pdf->writeHTML($html, false, false, false, false, '');

         $inWords = $this->convert_number($total);
         $text = "BILL PASSED FOR RUPEES ".$inWords;


         $pdf->SetFont('times', '', 14);
         $pdf->Ln();

         $pdf->Cell(0, 0, $text, 0, 0, 'L');


         $pdf->Ln();
         $pdf->Ln();

         $pdf->Ln();
         $pdf->Ln();
         $pdf->Ln();
         $html = '<table><tr><th>Store Manager</th><th>Deputy Warden</th><th>Hostel Warden</th></tr></table>';
         $pdf->writeHTML($html, false, false, false, false, '');


         //	$pdf->Output('C:\xampp\htdocs\cegMessStore\reports\report.pdf', 'FD');  //save pdf
         if(strpos($title,"Vegetable") !== false)
         $dir = "Vegetable Abstract/".$filename.".pdf";
       
         else
         $dir = "Items Abstract/".$filename.".pdf";

         $pdf->Output("/var/www/cegMessStore/reports/".$dir, 'FD');  //save pdf
         //		$pdf->Output('file.pdf', 'I'); // show pdf

         return true;
      }


      public function convert_number($number) {
         if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
         }
         $Gn = floor($number / 1000000);
         /* Millions (giga) */
         $number -= $Gn * 1000000;
         $kn = floor($number / 1000);
         /* Thousands (kilo) */
         $number -= $kn * 1000;
         $Hn = floor($number / 100);
         /* Hundreds (hecto) */
         $number -= $Hn * 100;
         $Dn = floor($number / 10);
         /* Tens (deca) */
         $n = $number % 10;
         /* Ones */
         $res = "";
         if ($Gn) {
            $res .= $this->convert_number($Gn) .  "Million";
         }
         if ($kn) {
            $res .= (empty($res) ? "" : " ") .$this->convert_number($kn) . " Thousand";
         }
         if ($Hn) {
            $res .= (empty($res) ? "" : " ") .$this->convert_number($Hn) . " Hundred";
         }
         $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
         $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
         if ($Dn || $n) {
            if (!empty($res)) {
               $res .= " and ";
            }
            if ($Dn < 2) {
               $res .= $ones[$Dn * 10 + $n];
            } else {
               $res .= $tens[$Dn];
               if ($n) {
                  $res .= "-" . $ones[$n];
               }
            }
         }
         if (empty($res)) {
            $res = "zero";
         }
         return $res;
      }


   }

