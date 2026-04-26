<?php
error_reporting(0);
ini_set('display_errors', 0);
require_once('includes/autoload.php');
define('FPDF_FONTPATH', dirname(__FILE__) . '/includes/fpdf/font/');
#[\AllowDynamicProperties]
class invoicr extends FPDF_rotation 
{
	var $currency = 'LKR';
	var $font = 'helvetica';
	var $columnOpacity = 0.06;
	var $columnSpacing = 0.3;
	var $referenceformat = array('.',',');
	var $margins = array('l'=>20,'t'=>20,'r'=>20);

	var $l;
	var $document;
	var $type;
	var $reference;
	var $logo;
	var $color;
	var $date;
	var $due;
	var $from;
	var $to;
	var $ship; // ADDED SHIPPING
	var $items;
	var $totals;
	var $badge;
	var $addText;
	var $footernote;
	var $dimensions;

	// function __construct($orientation='P', $unit='mm', $size='A4')
	// {
	// 	parent::__construct($orientation, $unit, $size);
	// 	$this->fontpath = 'C:/xampp/htdocs/Invoice/includes/fpdf/font/';
	// }

	function __construct($size='A4', $currency='LKR', $language='en')
	{
		$this->columns = 5;
		$this->items = array();
		$this->totals = array();
		$this->addText = array();
		$this->firstColumnWidth = 70;
		$this->currency = $currency;
		$this->maxImageDimensions = array(230,130);
		
		$this->setLanguage($language);
		$this->setDocumentSize($size);
		$this->setColor("#222222");
		
		parent::FPDF('P', 'mm', array($this->document['w'], $this->document['h']));
		$this->fontpath = dirname(__FILE__) . '/includes/fpdf/font/';
		$this->AliasNbPages();
		$this->SetMargins($this->margins['l'], $this->margins['t'], $this->margins['r']);
	}
	
	function setType($title)
	{
		$this->title = $title;
	}
	
	function setColor($rgbcolor)
	{
		$this->color = $this->hex2rgb($rgbcolor);
	}
	
	function setDate($date)
	{
		$this->date = $date;
	}
	
	function setDue($date)
	{
		$this->due = $date;
	}
	
	function setLogo($logo=0,$maxWidth=0,$maxHeight=0)
	{
		if($maxWidth and $maxHeight) {
			$this->maxImageDimensions = array($maxWidth,$maxHeight);
		}
		$this->logo = $logo;
		$this->dimensions = $this->resizeToFit($logo);
	}
	
	function setFrom($data)
	{
		$this->from = array_filter($data);
        //print_r(array_filter($data));
	}
	
	function setTo($data)
	{
		$this->to = $data;
	}

	function shipTo($data)
	{
		$this->ship = $data;
	}
	
	function setReference($reference)
	{
		$this->reference = $reference;
	}
	
	function setNumberFormat($decimals,$thousands_sep)
	{
		$this->referenceformat = array($decimals,$thousands_sep);
	}
	
	function flipflop()
	{
		$this->flipflop = true;
	}
	
	function addItem($item,$description,$quantity,$vat,$price,$total,$discount=0)
	{
		$p['item'] 			= $item;
		$p['description'] 	= $this->br2nl($description);
		$p['vat']			= $vat;
		if(is_numeric($vat)) {
			$p['vat']		= $this->currency.' '.number_format($vat,2,$this->referenceformat[0],$this->referenceformat[1]);
		} 
		$p['quantity'] 		= $quantity;
		$p['price']			= $price;
		$p['total']			= $total;
		
		if($discount!==false) {
			$this->firstColumnWidth = 58;
			$p['discount'] = $discount;
			if(is_numeric($discount)) {
				$p['discount']	= $this->currency.' '.number_format($discount,2,$this->referenceformat[0],$this->referenceformat[1]);
			}
			$this->discountField = true;
			$this->columns = 6;
		}
		
		$this->items[]		= $p;
	}

	function addTotal($name,$value,$colored=0)
	{
		$t['name']			= $name;
		$t['value']			= $value;
		if(is_numeric($value)) {
			$t['value']			= $this->currency.' '.number_format($value,2,$this->referenceformat[0],$this->referenceformat[1]);
		} 
		$t['colored']		= $colored;
		$this->totals[]		= $t;
	}
	
	function addTitle($title) 
	{
		$this->addText[] = array('title',$title);
	}
	
	function addParagraph($paragraph) 
	{
		$paragraph = $this->br2nl($paragraph);
		$this->addText[] = array('paragraph',$paragraph);
	}
	
	function addBadge($badge)
	{
		$this->badge = $badge;
	}
	
	function setFooternote($note) 
	{
		$this->footernote = $note;
	}
	
	function render($name='',$destination='')
	{
		$this->AddPage();
		$this->Body();
		$this->AliasNbPages();
		$this->Output($name,$destination);
	}
	
	/*******************************************************************************
	*                                                                              *
	*                               Create Invoice                                 *
	*                                                                              *
	*******************************************************************************/
	function Header()
	{
		$lineheight = 5;
		$pageWidth = $this->document['w'] - $this->margins['l'] - $this->margins['r'];

		// ── TOP HEADER BAND ──────────────────────────────────────────────
		// Left: Company branding block
		$this->SetXY($this->margins['l'], $this->margins['t']);

		// Company name - large and bold
		$this->SetFont($this->font, 'B', 22);
		$this->SetTextColor($this->color[0], $this->color[1], $this->color[2]);
		$this->Cell(100, 9, 'SHENAL HOLDINGS', 0, 2, 'L');

		// Tagline / sub-label under company name
		$this->SetFont($this->font, '', 8);
		$this->SetTextColor(130, 130, 130);
		$this->Cell(100, 5, 'Professional Services & Solutions', 0, 2, 'L');

		// ── Right: Invoice Title + Meta ──────────────────────────────────
		// Position to top-right
		$this->SetXY($this->margins['l'] + $pageWidth - 75, $this->margins['t']);

		// Invoice type title (INVOICE / QUOTE etc.)
		$this->SetFont($this->font, 'B', 18);
		$this->SetTextColor($this->color[0], $this->color[1], $this->color[2]);
		$this->Cell(75, 9, iconv("UTF-8", "ISO-8859-1", strtoupper($this->title)), 0, 2, 'R');

		// Meta rows: Number, Date, Due
		$labelW = 28;
		$valW   = 47;

		$metaItems = array(
			array($this->l['number'], $this->reference),
			array($this->l['date'],   $this->date),
		);
		if ($this->due) {
			$metaItems[] = array($this->l['due'], $this->due);
		}

		foreach ($metaItems as $row) {
			$curX = $this->margins['l'] + $pageWidth - $labelW - $valW;
			$curY = $this->GetY();

			// Label
			$this->SetXY($curX, $curY);
			$this->SetFont($this->font, 'B', 8);
			$this->SetTextColor($this->color[0], $this->color[1], $this->color[2]);
			$this->Cell($labelW, $lineheight, iconv("UTF-8", "ISO-8859-1", strtoupper($row[0]) . ':'), 0, 0, 'L');

			// Value
			$this->SetFont($this->font, '', 8);
			$this->SetTextColor(60, 60, 60);
			$this->Cell($valW, $lineheight, iconv("UTF-8", "ISO-8859-1", $row[1]), 0, 2, 'R');
		}

		// ── DIVIDER LINE ─────────────────────────────────────────────────
		$lineY = max($this->margins['t'] + 22, $this->GetY() + 4);
		$this->SetLineWidth(0.6);
		$this->SetDrawColor($this->color[0], $this->color[1], $this->color[2]);
		$this->Line($this->margins['l'], $lineY, $this->document['w'] - $this->margins['r'], $lineY);

		// ── ADDRESS BOX ROW ──────────────────────────────────────────────
		if ($this->PageNo() == 1) {
			$this->SetY($lineY + 5);
			$colW = $pageWidth / 3;

			// Column headers
			$this->SetFont($this->font, 'B', 7);
			$this->SetTextColor($this->color[0], $this->color[1], $this->color[2]);
			$this->Cell($colW, 5, strtoupper($this->l['from'] ?? ''), 0, 0, 'L');
			$this->Cell($colW, 5, strtoupper($this->l['to']   ?? ''), 0, 0, 'L');
			$this->Cell($colW, 5, strtoupper($this->l['ship'] ?? ''), 0, 1, 'L');

			// Thin separator under headers
			$this->SetLineWidth(0.2);
			$this->SetDrawColor(200, 200, 200);
			$headerLineY = $this->GetY();
			$this->Line($this->margins['l'], $headerLineY, $this->document['w'] - $this->margins['r'], $headerLineY);
			$this->Ln(2);

			// First name row bold
			$this->SetFont($this->font, 'B', 8);
			$this->SetTextColor(40, 40, 40);
			$this->Cell($colW, 5, iconv("UTF-8", "ISO-8859-1", $this->from[0] ?? ''), 0, 0, 'L');
			$this->Cell($colW, 5, iconv("UTF-8", "ISO-8859-1", $this->to[0]   ?? ''), 0, 0, 'L');
			$this->Cell($colW, 5, iconv("UTF-8", "ISO-8859-1", isset($this->ship[0]) ? $this->ship[0] : ''), 0, 1, 'L');

			// Remaining rows smaller
			$this->SetFont($this->font, '', 7);
			$this->SetTextColor(100, 100, 100);
			$fromCount = is_array($this->from) ? count($this->from) : 0;
			$toCount   = is_array($this->to)   ? count($this->to)   : 0;
			$shipCount = is_array($this->ship) ? count($this->ship) : 0;
			$maxRows   = max($fromCount, $toCount, $shipCount);
			for ($i = 1; $i < $maxRows; $i++) {
				$this->Cell($colW, 4, iconv("UTF-8", "ISO-8859-1", isset($this->from[$i]) ? $this->from[$i] : ''), 0, 0, 'L');
				$this->Cell($colW, 4, iconv("UTF-8", "ISO-8859-1", isset($this->to[$i])   ? $this->to[$i]   : ''), 0, 0, 'L');
				$this->Cell($colW, 4, iconv("UTF-8", "ISO-8859-1", isset($this->ship[$i]) ? $this->ship[$i] : ''), 0, 1, 'L');
			}
			$this->Ln(3);
		} else {
			$this->SetY($lineY + 5);
		}

		// ── TABLE HEADER ─────────────────────────────────────────────────
		if (!isset($this->productsEnded)) {
			$width_other = ($this->document['w'] - $this->margins['l'] - $this->margins['r']
				- $this->firstColumnWidth - ($this->columns * $this->columnSpacing)) / ($this->columns - 1);

			$this->Ln(4);
			$thY = $this->GetY();

			// Table header background
			$this->SetFillColor($this->color[0], $this->color[1], $this->color[2]);
			$this->Rect($this->margins['l'], $thY, $pageWidth, 8, 'F');

			$this->SetFont($this->font, 'B', 8);
			$this->SetTextColor(255, 255, 255);
			$this->SetY($thY);

			$this->Cell(1, 8, '', 0, 0, 'L', 0);
			$this->Cell($this->firstColumnWidth, 8, iconv("UTF-8", "ISO-8859-1", strtoupper($this->l['product'])), 0, 0, 'L', 0);
			$this->Cell($this->columnSpacing, 8, '', 0, 0, 'L', 0);
			$this->Cell($width_other, 8, iconv("UTF-8", "ISO-8859-1", strtoupper($this->l['amount'])), 0, 0, 'C', 0);
			$this->Cell($this->columnSpacing, 8, '', 0, 0, 'L', 0);
			$this->Cell($width_other, 8, iconv("UTF-8", "ISO-8859-1", strtoupper($this->l['vat'])),    0, 0, 'C', 0);
			$this->Cell($this->columnSpacing, 8, '', 0, 0, 'L', 0);
			$this->Cell($width_other, 8, iconv("UTF-8", "ISO-8859-1", strtoupper($this->l['price'])),  0, 0, 'C', 0);
			if (isset($this->discountField)) {
				$this->Cell($this->columnSpacing, 8, '', 0, 0, 'L', 0);
				$this->Cell($width_other, 8, iconv("UTF-8", "ISO-8859-1", strtoupper($this->l['discount'])), 0, 0, 'C', 0);
			}
			$this->Cell($this->columnSpacing, 8, '', 0, 0, 'L', 0);
			$this->Cell($width_other, 8, iconv("UTF-8", "ISO-8859-1", strtoupper($this->l['total'])),  0, 0, 'C', 0);
			$this->Ln();
			$this->Ln(2);
		} else {
			$this->Ln(12);
		}
	}

	function Body()
	{	
		$width_other = ($this->document['w']-$this->margins['l']-$this->margins['r']-$this->firstColumnWidth-($this->columns*$this->columnSpacing))/($this->columns-1);
		$cellHeight = 9;
		$bgcolor = (1-$this->columnOpacity)*255;

		if($this->items) {
			foreach($this->items as $item) 
			{
				// ── Pre-calculate description height ──
				if($item['description']) 
				{
					$calculateHeight = new invoicr;
					$calculateHeight->addPage();
					$calculateHeight->setXY(0,0);
					$calculateHeight->SetFont($this->font,'',7);	
					$calculateHeight->MultiCell($this->firstColumnWidth,3,iconv("UTF-8","ISO-8859-1",$item['description']),0,'L',1);
					$descriptionHeight = $calculateHeight->getY()+$cellHeight+2;
					$pageHeight = $this->document['h']-$this->GetY()-$this->margins['t']-$this->margins['t'];
					if($pageHeight < 0) 
					{
						$this->AddPage();
					}
				}

				// ── Pre-calculate product name height (may wrap) ──
				$calcName = new invoicr;
				$calcName->addPage();
				$calcName->SetFont($this->font,'B',8);
				$calcName->SetXY(0,0);
				$calcName->MultiCell($this->firstColumnWidth, 5, iconv("UTF-8","ISO-8859-1",$item['item']), 0, 'L', 1);
				$nameHeight = $calcName->GetY();

				// Row height: if no description, expand to fit wrapped name
				$cHeight = $cellHeight;
				if(!$item['description']) {
					$cHeight = max($nameHeight, $cellHeight);
				}

				$startY = $this->GetY();

				$this->SetFont($this->font,'B',8);
				$this->SetTextColor(50,50,50);
				$this->SetFillColor($bgcolor,$bgcolor,$bgcolor);

				// ── Left edge spacer cell ──
				$this->SetXY($this->margins['l'], $startY);
				$this->Cell(1, $cHeight, '', 0, 0, 'L', 1);
				$nameX = $this->GetX();

				// ── Product name as MultiCell (wraps if long) ──
				$this->SetXY($nameX, $startY);
				$this->MultiCell($this->firstColumnWidth, 5, iconv("UTF-8","ISO-8859-1",$item['item']), 0, 'L', 1);
				$afterNameY = $this->GetY();

				// Fill remaining space if name was shorter than cHeight
				if($afterNameY < $startY + $cHeight) {
					$this->SetXY($nameX, $afterNameY);
					$this->Cell($this->firstColumnWidth, ($startY + $cHeight) - $afterNameY, '', 0, 0, 'L', 1);
				}

				// ── Description block ──
				if($item['description'])
				{
					$resetX = $nameX;
					$resetY = $startY;

					$this->SetTextColor(120,120,120);
					$this->SetXY($nameX, $startY + $nameHeight + 1);
					$this->SetFont($this->font,'',7);			
					$this->MultiCell($this->firstColumnWidth, 3, iconv("UTF-8","ISO-8859-1",$item['description']), 0, 'L', 1);

					// Recalculate total row height including description
					$newY    = $this->GetY();
					$cHeight = $newY - $resetY + 2;

					// Stretch left spacer to full row height
					$this->SetXY($this->margins['l'], $resetY);
					$this->Cell(1, $cHeight, '', 0, 0, 'L', 1);

					// Bottom padding cell under description
					$this->SetXY($nameX, $newY);
					$this->Cell($this->firstColumnWidth, 2, '', 0, 0, 'L', 1);
				}

				// ── Data cells: qty, vat, price, discount, total ──
				$this->SetTextColor(50,50,50);
				$this->SetFont($this->font,'',8);
				$this->SetFillColor($bgcolor,$bgcolor,$bgcolor);

				// Pin data cells to top of row (startY)
				$this->SetXY($nameX + $this->firstColumnWidth, $startY);

				$this->Cell($this->columnSpacing, $cHeight, '', 0, 0, 'L', 0);
				$this->Cell($width_other, $cHeight, $item['quantity'], 0, 0, 'C', 1);
				$this->Cell($this->columnSpacing, $cHeight, '', 0, 0, 'L', 0);
				$this->Cell($width_other, $cHeight, iconv('UTF-8','windows-1252',$item['vat']), 0, 0, 'C', 1);
				$this->Cell($this->columnSpacing, $cHeight, '', 0, 0, 'L', 0);
				$this->Cell($width_other, $cHeight, iconv('UTF-8','windows-1252', $this->currency.' '.number_format($item['price'],2,$this->referenceformat[0],$this->referenceformat[1])), 0, 0, 'C', 1);

				if(isset($this->discountField)) 
				{
					$this->Cell($this->columnSpacing, $cHeight, '', 0, 0, 'L', 0);
					if(isset($item['discount'])) 
					{
						$this->Cell($width_other, $cHeight, iconv('UTF-8','windows-1252',$item['discount']), 0, 0, 'C', 1);
					} 
					else 
					{
						$this->Cell($width_other, $cHeight, '', 0, 0, 'C', 1);
					}
				}

				$this->Cell($this->columnSpacing, $cHeight, '', 0, 0, 'L', 0);
				$this->Cell($width_other, $cHeight, iconv('UTF-8','windows-1252', $this->currency.' '.number_format($item['total'],2,$this->referenceformat[0],$this->referenceformat[1])), 0, 0, 'C', 1);

				// Move cursor to next row
				$this->SetXY($this->margins['l'], $startY + $cHeight);
				$this->Ln($this->columnSpacing);
			}
		}

		$badgeX = $this->getX();
		$badgeY = $this->getY();

		// ── Totals ──
		if($this->totals) 
		{
			foreach($this->totals as $total) 
			{
				$this->SetTextColor(50,50,50);
				$this->SetFillColor($bgcolor,$bgcolor,$bgcolor);
				$this->Cell(1+$this->firstColumnWidth, $cellHeight, '', 0, 0, 'L', 0);
				for($i=0; $i<$this->columns-3; $i++) 
				{
					$this->Cell($width_other, $cellHeight, '', 0, 0, 'L', 0);
					$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
				}
				$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
				if($total['colored']) 
				{
					$this->SetTextColor(255,255,255);
					$this->SetFillColor($this->color[0],$this->color[1],$this->color[2]);
				}
				$this->SetFont($this->font,'B',8);
				$this->Cell(1, $cellHeight, '', 0, 0, 'L', 1);
				$this->Cell($width_other-1, $cellHeight, iconv('UTF-8','windows-1252',$total['name']), 0, 0, 'L', 1);
				$this->Cell($this->columnSpacing, $cellHeight, '', 0, 0, 'L', 0);
				$this->SetFont($this->font,'B',8);
				$this->SetFillColor($bgcolor,$bgcolor,$bgcolor);
				if($total['colored']) 
				{
					$this->SetTextColor(255,255,255);
					$this->SetFillColor($this->color[0],$this->color[1],$this->color[2]);
				}
				$this->Cell($width_other, $cellHeight, iconv('UTF-8','windows-1252',$total['value']), 0, 0, 'C', 1);
				$this->Ln();
				$this->Ln($this->columnSpacing);
			}
		}

		$this->productsEnded = true;
		$this->Ln();
		$this->Ln(3);

		// ── Badge ──
		if($this->badge) 
		{
			$badge = ' '.strtoupper($this->badge).' ';
			$resetX = $this->getX();
			$resetY = $this->getY();
			$this->setXY($badgeX, $badgeY+15);
			$this->SetLineWidth(0.4);
			$this->SetDrawColor($this->color[0],$this->color[1],$this->color[2]);		
			$this->setTextColor($this->color[0],$this->color[1],$this->color[2]);
			$this->SetFont($this->font,'B',15);
			$this->Rotate(10,$this->getX(),$this->getY());
			$this->Rect($this->GetX(),$this->GetY(),$this->GetStringWidth($badge)+2,10);
			$this->Write(10,$badge);
			$this->Rotate(0);
			if($resetY > $this->getY()+20) 
			{
				$this->setXY($resetX,$resetY);
			} 
			else 
			{
				$this->Ln(18);
			}
		}

		// ── Additional text ──
		foreach($this->addText as $text) 
		{
			if($text[0] == 'title') 
			{
				$this->SetFont($this->font,'B',9);
				$this->SetTextColor(50,50,50);
				$this->Cell(0,10,iconv("UTF-8","ISO-8859-1",strtoupper($text[1])),0,0,'L',0);
				$this->Ln();
				$this->SetLineWidth(0.3);
				$this->SetDrawColor($this->color[0],$this->color[1],$this->color[2]);
				$this->Line($this->margins['l'], $this->GetY(), $this->document['w']-$this->margins['r'], $this->GetY());
				$this->Ln(4);
			}
			if($text[0] == 'paragraph') 
			{
				$this->SetTextColor(80,80,80);
				$this->SetFont($this->font,'',8);
				$this->MultiCell(0,4,iconv("UTF-8","ISO-8859-1",$text[1]),0,'L',0);
				$this->Ln(4);
			}
		}
	}

	function Footer()
	{
		$this->SetY(-$this->margins['t']);
		$this->SetFont($this->font,'',8);
		$this->SetTextColor(50,50,50);
		$this->Cell(0,10,$this->footernote,0,0,'L');
		$this->Cell(0,10,$this->l['page'].' '.$this->PageNo().' '.$this->l['page_of'].' {nb}',0,0,'R');
	}
	
	/*******************************************************************************
	*                                                                              *
	*                               Private methods                                *
	*                                                                              *
	*******************************************************************************/
	private function setLanguage($language)
	{
		$this->language = $language;
		include('languages/'.$language.'.inc');
		$this->l = $l;
	}
	
	private function setDocumentSize($dsize)
	{
		switch ($dsize)
		{
			case 'A4':
				$document['w'] = 210;
				$document['h'] = 297;
				break;
			case 'letter':
				$document['w'] = 215.9;
				$document['h'] = 279.4;
				break;
			case 'legal':
				$document['w'] = 215.9;
				$document['h'] = 355.6;
				break;
			default:
				$document['w'] = 210;
				$document['h'] = 297;
				break;
		}
		$this->document = $document;
	}
	
	private function resizeToFit($image)
	{
		list($width, $height) = getimagesize($image);
		$newWidth = $this->maxImageDimensions[0]/$width;
		$newHeight = $this->maxImageDimensions[1]/$height;
		$scale = min($newWidth, $newHeight);
		return array(
			round($this->pixelsToMM($scale * $width)),
			round($this->pixelsToMM($scale * $height))
		);
	}
	    
	private function pixelsToMM($val) 
	{
		$mm_inch = 25.4;
		$dpi = 96;
		return $val * $mm_inch/$dpi;
	}
	
	private function hex2rgb($hex)
	{
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   return $rgb;
	}
	
	private function br2nl($string)
	{
    	return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
	}  

}

?>