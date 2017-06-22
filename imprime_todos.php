<?php
# Weby - Sistema de gerenciamento de conteúdo
# Copyright (C) 2011-2012  Universidade Federal de Goiás
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

//Inicia a sessão
session_start();

set_time_limit(3000);
//classe fpdf
include_once("fpdf/pdf.php");
$_REQUEST['tipo_acao'] = 'imprimir';

/*****************************   *********************************/
if(isset($_REQUEST['tipo_acao']) && $_REQUEST['tipo_acao'] == 'imprimir')
{	
	/*******configuracao dos formularios**************/
	//tamnhos de fontes e altura das celulas
	$sizeText 	   	= 7;
	$sizeCabecalho 	= 9;
	$altura        	= 4;
	
	//cor das celulas
	$r_padrao 	   	= 239;
	$g_padrao 		= 235;
	$b_padrao 		= 239;
	/*************************************************/
	
	$time = localtime();
	$sec  = $time[0];
	$min  = $time[1];
	$hour = $time[2];

	$pdf = new PDF('P');
								
	$print_header = true;
	//$pdf->SetAutoPageBreak('auto');
	
	//$imagem_esquerda = "UFG.jpg";

	$celCabecalho[0] = array("type" => "SetFont","famly" => 'Arial', "style" => 'B',"size" => $sizeCabecalho);
	$celCabecalho[1] = array("type" => "CELL","w" => 0, "h" => 0,"txt" => '', "border" => '0', "ln" => '1', "aling" =>'C');
	$celCabecalho[2] = array("type" => "CELL","w" => 0, "h" => $altura ,"txt" => utf8_decode('UFG - Universidade Federal de Goiás                                                              Centro de Recursos Computacionais - Equipe Web'), "border" => '0', "ln" => '1', "aling" =>'L');	
	//$celCabecalho[3] = array("type" => "CELL","w" => 0, "h" => $altura ,"txt" => utf8_decode(''), "border" => '0', "ln" => '1', "aling" =>'C');
	//$celCabecalho[4] = array("type" => "CELL","w" => 0, "h" => $altura ,"txt" => utf8_decode('Código fonte Weby'), "border" => '0', "ln" => '1', "aling" =>'C');
	
	$controle = 0;

	$contador = 1;
		
	$rodape = utf8_decode("Código")." fonte: Weby                                                                                                                                                            "."Data: ".date('d/m/Y')." ".$hour. ":" .$min. ":" . $sec;
	$pdf->AliasNbPages();
	$pdf->SetLineWidth(0.5);
	$pdf->AddPage();
	$pdf->Cell(0,1,'','T','1','L','0'); //linha	
	
	//######################################################################## pegar caminhos de todos os arquivos
	$dir = "/home/marcos/RoR/weby";//AQUI VC ALTERA O DIRETORIO	
	$arquivo = "";
	$i = 0;
	function varre($dir,$filtro="",$nivel="")
	{
	   global $arquivo, $i;
	   
	   $diraberto = dir($dir);
	   chdir($dir);
	   //echo $dir."<br>";	   
	   while($arq = $diraberto->read() ) {
		   //echo $dir."/".$arq."<br>";
		   if ($arq != "." && $arq != "..") 
		   {
			   if (is_dir($arq)) 
			   {
				   varre($dir."/".$arq);
			   }
			   elseif (is_file($arq))
			   {
				   if(substr(strtolower($arq),-4,4) != ".png"  && substr(strtolower($arq),-4,4) != ".jpg" 
					  && substr(strtolower($arq),-4,4) != ".gif" && substr(strtolower($arq),-4,4) != ".txt" 
					  && substr(strtolower($arq),-4,4) != ".csv" && substr(strtolower($arq),-4,4) != ".pdf" 
					  && substr(strtolower($arq),-4,4) != ".swf" && substr(strtolower($arq),-4,4) != ".ttf" 
            && substr(strtolower($arq),-4,4) != ".rtf" && substr(strtolower($arq),-4,4) != ".ico"
            && substr(strtolower($arq),-4,4) != ".idx" && substr(strtolower($arq),-5,5) != ".pack")
					{
					   $i++;
					   $arquivo[$i] = $dir."/".$arq;
					}
			   }		   
			   
		   }
	   }
	   chdir("..");
	   closedir($diraberto);
	}

	varre("$dir","","&nbsp;&nbsp;&nbsp;&nbsp;");
	
	//############################################################################################################
	
	$break = 0;
	$controle_css = 0;
	$count=0;
	
	$conta_arquivos=0;
	foreach($arquivo as $arq){
		$conta_arquivos++;		
		$caminho = $arq;
		//echo $caminho."<br>";
		$lines = file($caminho); //Lê arquivo
	
		$imprimiu_caminho = 0;
		
		if(sizeof($lines)>1)
		{
			for ($i = 0; $i < sizeof($lines); $i++){ //percorre todo o arquivo
				
				if($break > 65)
				{					
						//$pdf->SetFont('Arial','B',$sizeText+2);						
						$pdf->AddPage();
						$pdf->Cell(0,1,'','T','1','L','0'); //linha	
						$break = 0;
						//$pdf->Cell(0,$altura ,utf8_decode(str_replace("/var/www/testes/topdf/","",$caminho)),'0','1','C','0'); //Arquivo
						//$pdf->SetFont('Arial','B',$sizeText);
						//$pdf->Cell(0,1,'','B','1','L','0'); //linha
				}				
				if($controle_css == 0)
				{
						$r = $r_padrao;
						$g = $g_padrao;
						$b = $b_padrao;
						$controle_css = 1;
				}
				else
				{
						$r = 255;
						$g = 255;
						$b = 255;
						$controle_css = 0;
				}

				$pdf->SetFillColor($r, $g, $b);
				
				if($imprimiu_caminho == 0)
				{
					$imprimiu_caminho = 1;					
					$break++;
					$pdf->SetFont('Arial','BI',$sizeText);					
					$pdf->Cell(0,$altura ,utf8_decode(str_replace("/var/www/testes/topdf/","",$caminho)),'0','1','L',0); //Arquivo					
				}
				
				$pdf->SetFont('Arial','',$sizeText);
				$pdf->Cell(10,$altura ,$contador,'0','0','C','1');
				
				$pdf->Cell(0, $altura, utf8_decode(substr($lines[$i],0,110)), 0,1,'J', 1);
				$break++;				
				
				$contador++;
			}
		}
	}		
	//$pdf->Output();
	$pdf->Output('weby.pdf','F');
}
?>
