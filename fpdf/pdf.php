<?php
//echo realpath();
require('FPDF.php');
//error_reporting(E_ALL ^ E_NOTICE);
class PDF extends FPDF
{	
	//Page header
	function Header()
	{
		global $imagem_esquerda;
		global $imagem_direita;
		global $imagem_central;
		global $cabecalho;
		global $tam_font_cab;
		global $subcabecalho;
		global $print_header;
		global $celCabecalho; //criacao por  MAU: 31/01/2007
	    global $celSubCabecalho;//criacao por  MAU: 31/01/2007
	    //$imagem_esquerda = "";
	    
		if($print_header)
		{
			//Logo - Landscape
			if($this->CurOrientation == 'L')
			{
				if($imagem_esquerda != '' || $imagem_direita != '')
				{
					if($imagem_esquerda != '')
						$this->Image($imagem_esquerda,30,25,23);
						//$this->Image($imagem_esquerda,260,18,22);
					if($imagem_direita != '')
						$this->Image($imagem_direita,260,12,12);
						//$this->Image($imagem_direita,15,12,12);
				}
				elseif($imagem_central != '')
				{
					$this->Image($imagem_central,133,10,25);
					$this->SetY(40);
				}
			}
			//Portrait
			else
			{
			  if($imagem_esquerda != '' || $imagem_direita != '')
				{
					if($imagem_esquerda != '')
						$this->Image($imagem_esquerda,25,10,30);
						//$this->Image($imagem_esquerda,177,16,18);
					if($imagem_direita != '')
						$this->Image($imagem_direita,177,10,20);
						//$this->Image($imagem_direita,12,10,10);
				}
				elseif($imagem_central != '')
				{
					$this->Image($imagem_central,89,10,25);
					$this->SetY(40);
				}
			}
			if($tam_font_cab == '')
				$tam_font_cab = 13;
			//Arial bold
			$this->SetFont('Arial','B',$tam_font_cab);
			//Move to the right
			if($this->CurOrientation == 'L')
			{
			   $this->Cell(35);
			}  
			else
			{
				$this->Cell(17);
			} 
			//Title
			//Landscapeecho 'merda';
		
			if($this->CurOrientation == 'L')
			{
				$this->f_cabecalho($celCabecalho,$cabecalho);
				//echo '1';
				
			}
			//Portrait
			else
			{
				$this->f_cabecalho($celCabecalho,$cabecalho);
				//echo '2';
			}		
			//linha
			$y=$this->GetY();
			//if ($y < 42)
				//$y = 42;
			if(is_array($celSubCabecalho)){
			//echo '3';
					$this->f_cabecalho($celSubCabecalho,'');
				
			}
			else{
		
				$this->SetY($y);
				$this->SetFont('Arial','B',10);
				//$this->MultiCell(0,5,$subcabecalho,'B','L');
				$this->Cell(0,0,$subcabecalho,'0',0,'0');				
				$this->Ln(5);
				$this->SetY($y);
			}
			
		}
	}
	
	function f_cabecalho($Cabecalho,$cabecalho){
	//criada por MAU--data 31/01/2007
	//Motivo: dar maior liberdade para criacao do cabechalho;
		if(is_array($Cabecalho)){
			//print_r($Cabecalho);
			foreach($Cabecalho as $valor){
				if(is_array($valor)){
				
					if(in_array("CELL",$valor)){
						//entao e um cel
						
						if (array_key_exists("w", $valor))
							 $w = $valor['w'];
						else
							 $w = 0;
						
						if (array_key_exists("h", $valor))
							 $h = $valor['h'];
						else
							 $h = 5;
						
						if (array_key_exists("txt", $valor))
							 $txt = $valor['txt'];
						else
							 $txt = '';
						
						if (array_key_exists("border", $valor))
							 $border = $valor['border'];
						else
							 $border = 0;
						
						if (array_key_exists("ln", $valor))
							 $ln = $valor['ln'];
						else
							 $ln = 0;
						
						if (array_key_exists("aling", $valor))
							 $align = $valor['aling'];
						else
							 $align = '';
						//echo $txt.' '.$w.'<br>';
						$this->Cell($w,$h,$txt,$border,$ln,$align);
						
					}//if(in_array("cell",$valor){
					elseif(in_array("SetFont",$valor)){
							//set font
							if (array_key_exists("family", $valor))
								 $family = $valor['family'];
							else
								 $family = 'Arial';
								 
							if (array_key_exists("style", $valor))
								 $style = $valor['style'];
							else
								 $style = '';
							
							if (array_key_exists("size", $valor))
								 $size = $valor['size'];
							else
								 $size = '10';
								 
							$this->SetFont($family,$style,$size);
						
						}//elseif(if(in_array("SetFont",$valor){
						elseif(in_array("SetY",$valor)){
								//set y
								if (array_key_exists("y", $valor))
									 $newY = $valor['y'];
								else
									 $newY = 0;
								
								$this->SetY($y+$newY);
							}//elseif(in_array("SetY",$valor)){
							elseif(in_array("Ln",$valor)){
							
								if (array_key_exists("Lnsize", $valor))
									 $Lnsize = $valor['Lnsize'];
								else
									 $Lnsize = 5;
								$this->Cell(0,$Lnsize,'','T',1);
								//$this->Ln(5);
							}
						
				}//foreach($celCabecalho as $valor){ 
			} 
		}//f(is_array($celCabecalho)){
		else{
			if($cabecalho == '')
				$this->Error('Cabecalho em branco!');
			else
				$this->MultiCell(200,5,$cabecalho,0,'C');
		}
	}
	
	//Page footer
	function Footer()
	{
		global $rodape;
		global $legenda;
		//Position at 1.5 cm from bottom
		$var_t = 'T';
		
		if(isset($legenda) && trim($legenda) != '')
		{
			$var_t = '0';
			$this->SetY(-20);
			
			$this->SetFont('Arial','B',7);
			//data
			
			$this->MultiCell(0,3,trim($legenda),'T','L','0');

		}
		
		$this->SetY(-15);
		//Printar linha
		
		//Arial italic 8
		$this->SetFont('Arial','',8);
		//data
		
		$this->Cell(0,8,$rodape,$var_t,0,'L');
		$this->SetY(-16);
		//Page number
		$this->Cell(0,10,'Página '.$this->PageNo().'/{nb}','0',0,'C');
		
	}
	
	function Titulo($texto, $tamanho_linha,$fill=0, $align='L')
	{
		//Arial 12
		$this->SetFont('Times','',12);
		//Title
		$this->Cell(0,$tamanho_linha,$texto,1,1,$align,$fill);
	}
	
	function SetCol($col)
	{
		//Set position at a given column
		if($this->CurOrientation == 'L')
			$hardcode = 5;
		else
		    $hardcode = 10;	
		$this->col=$col;
		$x=$hardcode+$col*($this->w-$this->rMargin-$this->lMargin)/2;
		$this->SetLeftMargin($x);
		$this->SetX($x);
	}
	
	function AcceptPageBreak()
	{
		//Method accepting or not automatic page break
		if($this->col<1 && $this->y0 != -1)
		{
			//Go to next column
			$this->SetCol($this->col+1);
			//Set ordinate to top
			$this->SetY($this->y0);
			//Keep on page
			return false;
		}
		else
		{
			//Go back to first column
			$this->SetCol(0);
			//Page break
			return true;
		}
	}
	
	function CampoTexto($texto, $tamanho_linha, $quantidade_linhas=0)
	{
		//Arial 12
		$this->SetFont('Times','I',12);
		$texto = trim($texto);
		if($quantidade_linhas!=0)
			$quantidade_linhas++;
		if($quantidade_linhas > 2)
		{
			$this->y0 = $this->GetY();
			//area util de escrita
			$area_util = $this->w - $this->rMargin - $this->lMargin;
			//calculando a quantidade de linhas q serão printadas, levando em consideraçao a divisao da tela
			$pieces = explode(chr(13), $texto);
			$l=0;
			foreach($pieces as $res)
			{
				$l += ceil($this->GetStringWidth($res)/($area_util/2));
			}
			//caso o que vai ser printado nao caiba na pagina, pula pra proxima pagina
			if( (ceil($l/2) * $tamanho_linha) > ($this->h - $this->y0 - $this->bMargin) )
			{
				$this->AddPage();
				$this->y0 = $this->GetY();
			}
			$y=$this->y0+ceil($l/2)*$tamanho_linha;
			//Atenção!!! nâo altere o formato deste codigo!!! ele influencia na organização do texto!!!!
			if(($l % 2) != 0)
				$texto .='
 ';
 			//subindo a margem de baixo
			$this->SetAutoPageBreak(1, $this->h - $y);
			$this->MultiCell($area_util/2,$tamanho_linha,$texto,1,'L');
			//voltando a margem a 2cm
			$this->SetAutoPageBreak(1, 20);
			//volta a posiçao inicial para a primeira coluna
			$this->SetCol(0);
			$this->SetY($y);
			//linha da parte de cima e da de baixo do multicell dividido, respectivamente
			$this->Line($this->lMargin, $this->y0, $this->w-$this->rMargin, $this->y0);
			$this->Line($this->lMargin, $y, $this->w-$this->rMargin, $y);
			$this->y0 = -1;
		}
		else
		{
			//conteudo normal
			$this->MultiCell(0,$tamanho_linha,$texto,1,'J');
		}
	}
	//cria os nomes de cada coluna
	function NomeTabela($nome_colunas, $tamanho_colunas, $tamanho_linha, $alinhamento='L', $fill=0)
	{
		$this->SetFont('Times','',12);
		//colocar o nome de cada coluna 
		$j = 0;
		foreach($nome_colunas as $col)
			$this->Cell($tamanho_colunas[$j++],$tamanho_linha,$col,1,0,$alinhamento, $fill);
		$this->Ln();
	}
	//grades de acordo com o y no inicio e no fim, e com a quantidade de elementos em titulos
	function GradeColunasTabela($y_inicio, $y_fim, $porcentagem_colunas)
	{
		$quantidade_colunas = count($porcentagem_colunas);
		$area_util_horizontal = $this->w - $this->rMargin - $this->lMargin;
		//loop imprimindo uma grade para cada coluna
		$x = $this->lMargin;
		$this->Line($x, $y_inicio, $x, $y_fim);
		for($i = 0; $i <= $quantidade_colunas; $i++)
		{
			//posicao x da grade
			$x += $area_util_horizontal * $porcentagem_colunas[$i];
			$this->Line($x, $y_inicio, $x, $y_fim);
		}
	}
	//printa uma tabela, com nome , conteudo(data), tamanho da linha, porcentagem de cada coluna(a soma de todos deve ser igual a 1),
	//como ou sem borda(1 ou 0)(padrao 1), alinhamento dos nomes(padrao left), preenchimento das células dos nomes(não),
	//repeticao dos dados independente de serem duplicados ou nao(nao).
	function Tabela($nome_colunas, $data, $tamanho_linha, $porcentagem_colunas, $borda=1, $alinhamento = 'L', $fill=0, $repeticao=0)
	{
		if(count($porcentagem_colunas) != count($nome_colunas))
			$this->Error('Array do nome está diferente do array da porcentagem');
		$k = 0;
		$util = $this->w - $this->rMargin - $this->lMargin;
		foreach($porcentagem_colunas as $trem)
		{
			$tamanho_colunas[$k++] = $util * $trem;
		}
		//tamanho de cada coluna como a area horizontal util dividida pela quantidade de colunas
		//$tamanho_colunas = ($this->w - $this->rMargin - $this->lMargin)/count($nome_colunas);
		//titulos
		$this->NomeTabela($nome_colunas, $tamanho_colunas, $tamanho_linha, $alinhamento, $fill);
		//vairavel do inicio da tabela
		$comeco_tabela_y = $this->GetY();
		//fonte de conteúdo
		$this->SetFont('Times','I',12);
		//loop passando por cada linha
		for($i = 0; $i < count($data); $i++)
		{
			//variavel q conta as sub-linhas
			$quantidade_sub_linha = 0;
			//loop passando por cada coluna, verificando a coluna que ocupa mais sub-linhas
			for($j = 0; $j < count($data[$i]); $j++)
			{
				$data[$i][$j] = trim($data[$i][$j]);
				//divide o string em varios, dividindo por "ESPAÇO"
				$pieces = explode(' ', $data[$i][$j]);
				$l = 1;
				$total_espaco = 0;
				
				for($co = 0; $co < count($pieces); $co ++)
				{
					//$this->MultiCell(0,$tamanho_linha,'começo loop '.$co.' total_espaço: '.$total_espaco.' l: '.$l,0,'L');
					//echo 'começo loop '.$co.'<br>total_espaço: '.$total_espaco.'<br>l: '.$l.'<br>';
					$x = substr_count($pieces[$co], chr(13));
					//leva em consideração os caracteres "enter" dentro do string
					if($x > 0)
					{
						$l += $x;
						$total_espaco = 0;
					}
					//para cada pedaço, calcula a quantidade de linhas necessarias para printar o string
					//$this->MultiCell(0,$tamanho_linha,'pieces: '.$pieces[$co].' width: '.$this->GetStringWidth($pieces[$co]),0,'L');
					//echo 'pieces: '.$pieces[$co].'<br>width: '.$this->GetStringWidth($pieces[$co]).'<br>';
					$total_espaco += $this->GetStringWidth($pieces[$co]);
					//$this->MultiCell(0,$tamanho_linha,'total_espaço: '.$total_espaco,0,'L');
					//$this->MultiCell(0,$tamanho_linha,'limite: '.ceil($tamanho_colunas[$j]),0,'L');
					//echo 'total_espaço: '.$total_espaco.'<br>';
					if(ceil($total_espaco) > ceil($tamanho_colunas[$j]))
					{
						//$this->MultiCell(0,$tamanho_linha,'dentro do if!',0,'L');
						//echo 'dentro do if!<br>';
						$total_espaco = 0;
						$l ++;
						$co --;
					}
					$total_espaco += $this->GetStringWidth(' ');
					//limitador p/ ñ entrar em loop infinito
					if($l == 15)
					{
						$this->Error('Erro na tabela - Existem palavras que ocupam mais espaço que o fornecido em algum coluna!');
						break;
					}
				}

				/*
				if($this->CurOrientation == 'L')
			    	$espaco_hardcode = 20;
			    else 		
				   	$espaco_hardcode = 30;	
				foreach($pieces as $res)
				{
					//para cada pedaço, calcula a quantidade de linhas necessarias para printar o string
					$l += ceil(($this->GetStringWidth($res)+$espaco_hardcode)/$tamanho_colunas[$j]);
				}*/
				if($quantidade_sub_linha < $l)
					$quantidade_sub_linha = $l;
			}
			//caso a linha a ser printada nao caiba na pagina, pula para a proxima pagina
			if($quantidade_sub_linha * $tamanho_linha > $this->h - $this->GetY() - $this->bMargin)
			{
				//printa a ultima linha
				$this->Line($this->lMargin, $y, $this->w - $this->rMargin, $y);
				//printa as grades da pagina que acabou
				$this->GradeColunasTabela($comeco_tabela_y, $this->GetY(), $porcentagem_colunas);
				//adiciona outra pagina
				$this->AddPage();
				//nome das colunas novamente na nova pagina
				$this->NomeTabela($nome_colunas, $tamanho_colunas, $tamanho_linha, $alinhamento, $fill);	
				//variavel do novo começo de tabela
				$comeco_tabela_y = $this->GetY();
				//variavel indicando nova pagina
				$nova_pagina = 1;
				$this->SetFont('Times','I',12);
			}
			//loop passando por cada coluna, printando
			for($j = 0; $j < count($data[$i]); $j++)
			{
				//variaves x e y antes de printar a multicell
				$x=$this->GetX();
				$y=$this->GetY();
				//printa os dados se forem diferentes da linha anterior
				if($data[$i-1][$j] != $data[$i][$j] || $nova_pagina == 1 || $repeticao == 1)
					$this->MultiCell($tamanho_colunas[$j],$tamanho_linha,$data[$i][$j],0,'L');
				//como a multicell depois de printar, faz uma quebra de pagina, vou recolocar ao lado da mesma
				$this->SetXY($x+$tamanho_colunas[$j],$y);
				//coordenada y da linha de baixo
				$y = $this->GetY() + $quantidade_sub_linha * $tamanho_linha;
				//se borda for igual a 1
				if(($borda == 1 && $data[$i+1][$j] != $data[$i][$j]) || $data[$i][$j] == '' || $repeticao == 1)
					//printa a linha de baixo
					$this->Line($x, $y, $this->GetX(), $y);
			}
			//variavel indicando que ja passou pelo inicio da nova pagina
			$nova_pagina = 0;
			//seta o cursor na nova linha, na margem esquerda
			$this->SetXY($this->lMargin,$y);
		}
		//printa a ultima linha
		$this->Line($this->lMargin, $y, $this->w - $this->rMargin, $y);
		//printa as grades da pagina que acabou
		$this->GradeColunasTabela($comeco_tabela_y, $this->GetY(), $porcentagem_colunas);
		if(count($data) == 0)
		{
			$this->MultiCell(0,$tamanho_linha,"Não cadastrado",1,'L');
		}
	}
	
	function ImprimeTitulo($titulo, $descricao,$f_size = 10, $f_y = 12, $f_h  = 7)
	{
		$this->SetFont('Arial','B',$f_size);
		
		$tamanho = strlen($descricao)+13;
		
		$y = $this->GetY();
	
		$this-> SetY($y-$f_y);
		
		$this->Cell($tamanho,$f_h,$descricao,'0','0','L');
		$this->Cell(100,$f_h,$titulo,'0','0','L');
		//$this-> SetY($y);
	}	
}

?>
