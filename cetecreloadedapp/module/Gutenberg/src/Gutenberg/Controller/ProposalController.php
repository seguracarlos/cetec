<?php
/**
 * PHPPowerPoint
 *
 * Copyright (c) 2009 - 2010 PHPPowerPoint
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPPowerPoint
 * @package    PHPPowerPoint
 * @copyright  Copyright (c) 2009 - 2010 PHPPowerPoint (http://www.codeplex.com/PHPPowerPoint)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt  LGPL
 * @version    0.1.0, 2009-04-27
 */

namespace Gutenberg;

use BaseController;
use Application_Service_Impl_ProposalsServiceImpl;
use Zend_View_Helper_PaginationControl;
use Zend_Paginator;
use Zend_Loader_Autoloader;
use Zend_Pdf;
use Zend_Pdf_Page;
use Zend_Pdf_Font;
use Zend_Pdf_Color_Rgb;
use Zend_Pdf_Image;
use Zend_Pdf_Style;
use Zend_Pdf_Color_RGB;
use ProposalService;
use Zend_Pdf_Exception;
use Exception;
use proposalDao;
use PHPPowerPoint;
use PHPPowerPoint_Style_Alignment;
use PHPPowerPoint_Style_Color;
use PHPPowerPoint_IOFactory;




/** Error reporting */
//error_reporting(E_ALL);

/** Include path **/
//set_include_path(get_include_path() . PATH_SEPARATOR . '../Classes/');

/** PHPPowerPoint */
//include 'PHPPowerPoint.php';

/** PHPPowerPoint_IOFactory */
//include 'PHPPowerPoint/IOFactory.php';
include_once APPLICATION_PATH . '/controllers/BaseController.php';
include_once APPLICATION_PATH . '/services/impl/ProposalsServiceImpl.php';
require_once 'Zend/Loader/Autoloader.php';
require_once 'Zend/Pdf.php';

class ProposalController extends BaseController{

	public function init(){
		BaseController::init();
	}

	public function indexAction(){
		 
		//linea para tronar el recurso
//		echo $this->baseUrl();

		$prop = new Application_Service_Impl_ProposalsServiceImpl();
		$propuestas = $prop->listar();

		Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator/items.phtml');

		$paginatorP2 = Zend_Paginator::factory($propuestas);
		if($this->_hasParam('page')){
			$paginatorP2->setCurrentPageNumber($this->_getParam('page'));
		}
		$this->view->paginatorP2 = $paginatorP2;
	}

	/* ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
	 * ;;;;;;;; Export  Action ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
	 * ;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
	 */
	public function exportAction(){

		/*    +----------------------+
		 *    | __________________ + |
		 *    |                      |
		 *    |        eeeee         |
		 *    |  _________________   |
		 *    |  _________________   |
		 *    |        aaaaaa        |
		 *    |  _________________   |
		 *    |  _________________   |
		 *    |       aaaaaaa        |
		 *    |   - _____________    |
		 *    |   - _____________    |
		 *    |                      |
		 *    +----------------------+
		 */

		// register auto-loader
		$loader = Zend_Loader_Autoloader::getInstance();


		if(!$this->_hasParam('idProposals')){
			return $this->_redirect('/proposal/index');
		}
		if ($this->getRequest()->isPost()) {
			$prop = $this->getRequest()->getPost('export');
			if ($prop == 'Si') {

				try{

					$pdf = new Zend_Pdf();

					// create A4 page
					$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);

					// define font resource
					$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);

					 
					$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
					$page->drawLine(10, 820, ($page->getWidth()-10), 820);
					$page->drawLine(10, 25, ($page->getWidth()-10), 25);
					header('Content-type: application/pdf');
					/*
					 * Cuerpo del PDF
					 */
					$ruta = 'img/IOFractalLogoSM.png';
					$arbol = Zend_Pdf_Image::imageWithPath($ruta);
					$page->drawImage($arbol, 500, 740, 580, 820);


					//letras de color verde
					$letraVerde = new Zend_Pdf_Style();
					$letraVerde->setFillColor(new Zend_Pdf_Color_RGB(0, 10, 0));
					$letraVerde->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 12);
					//letras de color azul
					$letraAzul = new Zend_Pdf_Style();
					$letraAzul->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 10));
					$letraAzul->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 12);
					//letras de color negro
					$letraNegra = new Zend_Pdf_Style();
					$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
					$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
					//letras de color negro de titulos
					$letraNegraTit = new Zend_Pdf_Style();
					$letraNegraTit->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
					$letraNegraTit->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 14);

					$id = $this->_getParam('idProposals');
					 
					$propu = new ProposalService();
					$antecedent = $propu->getAntecedent($this->_getParam('idProposals'));
					$objective = $propu->getObjective($this->_getParam('idProposals'));
					$propSolution = $propu->getPropSolution($this->_getParam('idProposals'));
					$targetAudience = $propu->getTargetAudience($this->_getParam('idProposals'));
					 
					$page->setStyle($letraVerde);
					$page->drawText('Folio ', 40, 770);
					$page->drawText($id, 70, 770);
					//fecha del dia en que es creada la minuta en pdf
					$fecha = date('M j Y');
					$hora = date('h:i:s');
					//dibuja fecha
					$page->drawText('Fecha: ', 330, 765);
					$page->drawText($fecha, 380, 765);
					$page->drawText('Hora: ', 330, 755);
					$page->drawText($hora, 380, 755);

					//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
					//:::::::::::::::::: primera face informacion de empresa :::::::::::::::
					//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

					$page->setStyle($letraAzul);
					$page->drawText('INFORMACION DE LA EMPRESA', 40, 750);
					$page->drawText('_______________________________________________________________________________', 30, 740);


					$page->setStyle($letraNegraTit);
					//antecedentes
					$page->drawText('ANTECEDENTES ', 220, 720);
					$page->setStyle($letraNegra);
					$startActionAnt = 710;
					$cadeAnt = nl2br($antecedent);
					$cadeAnt = strip_tags($cadeAnt);
					$cadeAnt = wordwrap($cadeAnt , 155, '\n');
					$lineasAnt = explode('\n', $cadeAnt);

					foreach ($lineasAnt as $lineAnt) {
						$lineAnt = ltrim($lineAnt);
						$page->drawText($lineAnt, 40, $startActionAnt);
						$startActionAnt = $startActionAnt - 10;
					}
					$page->setStyle($letraNegraTit);
					//objective
					$page->drawText('OBJETIVO ', 230, $startActionAnt-10);
					$page->setStyle($letraNegra);
					$startActionObj = $startActionAnt-25;
					$cadeObj = nl2br($objective);
					$cadeObj = strip_tags($cadeObj);
					$cadeObj = wordwrap($cadeObj , 155, '\n');
					$lineasObj = explode('\n', $cadeObj);

					foreach ($lineasObj as $lineObj) {
						$lineObj = ltrim($lineObj);
						$page->drawText($lineObj, 40, $startActionObj);
						$startActionObj = $startActionObj - 10;
					}
					$page->setStyle($letraNegraTit);
					//solucion propuesta
					$page->drawText('SOLUCION PROPUESTA', 210, $startActionObj-10);
					$page->setStyle($letraNegra);
					$startActionPs = $startActionObj-25;
					$cadePs = strip_tags($propSolution);
					$cadePs = wordwrap($cadePs , 155, '\n');
					$lineasPs = explode('\n', $cadePs);
					foreach ($lineasPs as $linePs) {
						$linePs = ltrim($linePs);
						$page->drawText($linePs, 40, $startActionPs);
						$startActionPs = $startActionPs - 10;
					}
					$page->setStyle($letraNegraTit);
					//solucion propuesta
					$page->drawText('AUDIENCIA OBJETIVO ', 210, $startActionPs-10);
					$page->setStyle($letraNegra);
					$startActionAo = $startActionPs-25;
					$cadeAo = nl2br($targetAudience);
					$cadeAo = strip_tags($cadeAo);
					$cadeAo = wordwrap($cadeAo , 155, '\n');
					$lineasAo = explode('\n', $cadeAo);

					foreach ($lineasAo as $lineAo) {
						$lineAo = ltrim($lineAo);
						$page->drawText($lineAo, 40, $startActionAo);
						$startActionAo = $startActionAo - 10;
					}

					//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
					//::::::::::::::: 3 face imagenes :::::::::::::::::::::::::::::
					//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
					$propuesta = new ProposalService();
					$team = $propuesta->getProposedTeam($this->_getParam('idProposals'));
					$matAnal = $propuesta->getMatrixAnalysis($this->_getParam('idProposals'));
					$program = $propuesta->getProgram($this->_getParam('idProposals'));
					$investment = $propuesta->getInvestment($this->_getParam('idProposals'));

					$startTitulo4 = $startActionAo-20;
					$page->setStyle($letraAzul);
					$page->drawText('METODOLOGIA SELECCIONADA', 40, $startTitulo4);
					$page->drawText('________________________________________________________________________', 30, $startTitulo4-5);
					$page->setStyle($letraNegra);
					$startMet = $startTitulo4-23;
					$defMet = 'IOFractal opta por beneficios en métodos ágiles de desarrollo. El proyecto será guiado por SCRUM, un framework de alta disponibilidad a cambios y bajo impacto en tiempos de adaptación, así como revisiones constantes y entregas a tiempo.';
					$scrum = Zend_Pdf_Image::imageWithPath('img/scrum.png');
					$anchoImg = 40;

					//       4
					//  +-------------+
					//   |             |
					//  1|             |3
					//   |             |
					//   +-------------+
					//          2
					$page->drawImage($scrum, $anchoImg, $startMet-50, 200, $startMet+5);

					$cadeMet = strip_tags($defMet);
					$cadeMet = wordwrap($cadeMet, 100, '\n');
					$lineasMet = explode('\n', $cadeMet);
					foreach ($lineasMet as $lineMet){
						$lineMet = ltrim($lineMet);
						$page->drawText($lineMet, 200, $startMet-10);
						$startMet = $startMet -10;
					}
					 
					$startTitulo5 = $startMet-40;
					$page->setStyle($letraAzul);
					$page->drawText('EQUIPO PROPUESTO', 50, $startTitulo5);
					$page->drawText('________________________________', 30, $startTitulo5-5);
					$startTeam1 = $startTitulo5-15;
					$startTeam = $startTitulo5-70;
					$teamImg = Zend_Pdf_Image::imageWithPath($team);
					$page->drawImage($teamImg, 25, $startTeam-80, 280, $startTeam1);

					$page->drawText('MATRIZ DE ANALISIS', 340, $startTitulo5);
					$page->drawText('________________________________', 300, $startTitulo5-5);
					$startMatrix = $startTitulo5-15;
					$matrixImg = Zend_Pdf_Image::imageWithPath($matAnal);
					$page->drawImage($matrixImg, 300, $startMatrix-135, 550, $startMatrix);

					$page->drawText('PROGRAMA', 50, $startTeam-95);
					$page->drawText('________________________________', 30, $startTeam-100);
					$startProgram = $startTeam-110;
					$programImg = Zend_Pdf_Image::imageWithPath($program);
					$page->drawImage($programImg, 25, $startProgram-145, 280, $startProgram);

					$page->drawText('INVERSION', 340, $startMatrix-150);
					$page->drawText('________________________________', 300, $startMatrix-155);
					$startInvest = $startMatrix-165;
					$investImg = Zend_Pdf_Image::imageWithPath($investment);
					$page->drawImage($investImg, 300, $startInvest-145, 550, $startInvest);

					 
					//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
					//:::::::::::::::::::::::: segunda face de las propuestas :::::::::::::::::
					//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

					$startTitulo2 = $startInvest-160;
					if ($startTitulo2 < 80){

						array_push($pdf->pages, $page);
						$page = new Zend_Pdf_Page(
						Zend_Pdf_Page::SIZE_A4);
						//letras de color negro
						$letraNegra = new Zend_Pdf_Style();
						$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
						$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
						$page->setStyle($letraNegra);

						$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
						$page->drawLine(10, 820, ($page->getWidth()-10), 820);
						$page->drawLine(10, 25, ($page->getWidth()-10), 25);

						$startTitulo2 = 800;

					}
					$page->setStyle($letraAzul);
					$page->drawText('ROLES POSIBLES ', 40, $startTitulo2);
					$page->drawText('________________________________________________________________________', 30, $startTitulo2-5);
					 
					$usuario = $propu->getUsuario($this->_getParam('idProposals'));
					$cliente = $propu->getCliente($this->_getParam('idProposals'));
					$distribuidor = $propu->getDistribuidor($this->_getParam('idProposals'));
					$administrador = $propu->getAdministrador($this->_getParam('idProposals'));
					 
					$page->setStyle($letraNegra);
					$startRoles = $startTitulo2;

					if($usuario == 1){
						$startUsuario = $startTitulo2-20;
						$defUsuario = 'USUARIO: ususususuususususususuusususususuusususususuusususuususus';
						$cadeUs = strip_tags($defUsuario);
						$cadeUs = wordwrap($cadeUs, 155, '\n');
						$lineasUS = explode('\n', $cadeUs);
						foreach ($lineasUS as $lineUs){

							if ($startUsuario < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startUsuario = 800;

							}

							$lineUs = ltrim($lineUs);
							$page->drawText($lineUs, 40, $startUsuario);
							$startUsuario = $startUsuario -10;
						}
					}else{
						$startUsuario = $startTitulo2;
					}
					if($cliente == 1){
						$startCliente = $startUsuario-15;
						$defCliente = 'Cliente : Visualiza y actualiza cierta información personal y administra sus puntos así como conoce detalles y noticias';
						$cadeCl = strip_tags($defCliente);
						$cadeCl = wordwrap($cadeCl, 155, '\n');
						$lineasCl = explode('\n', $cadeCl);
						foreach ($lineasCl as $lineCl){

							if ($startCliente < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startCliente = 800;

							}

							$lineCl = ltrim($lineCl);
							$page->drawText($lineCl, 40, $startCliente);
							$startCliente = $startCliente-10;
						}
					}else{
						$startCliente = $startUsuario;
					}

					if($administrador == 1){
						$startAdmi = $startCliente-15;
						$defAdmi = 'Administrador : Administra las cuentas de los clientes adscritos a su agencia, así como genera noticias y promociones, permite canjees';
						$cadeAd = strip_tags($defAdmi);
						$cadeAd = wordwrap($cadeAd , 155, '\n');
						$lineasAd = explode('\n', $cadeAd);
						foreach ($lineasAd as $lineAd) {

							if ($startAdmi < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startAdmi = 800;

							}

							$lineAd = ltrim($lineAd);
							$page->drawText($lineAd, 40, $startAdmi);
							$startAdmi = $startAdmi - 10;
						}
					}else{
						$startAdmi = $startCliente;
					}
					if($distribuidor == 1){
						$startDis = $startAdmi-15;
						$defDis = 'Distribuidor : Corporativo: Permite hacer una comunicación a la gente de Mitsubish.';
						$cadeDis = strip_tags($defDis);
						$cadeDis = wordwrap($cadeDis , 155, '\n');
						$lineasDis = explode('\n', $cadeDis);
						foreach ($lineasDis as $lineDis) {

							if ($startDis < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startDis = 800;

							}

							$lineDis = ltrim($lineDis);
							$page->drawText($lineDis, 40, $startDis);
							$startDis = $startDis - 10;
						}
					}else{
						$startDis = $startAdmi;
					}

					//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
					//::::::::::::::: face de tecnologias :::::::::::::::::::::::::
					//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
					$startTitulo3 = $startDis-30;
					if ($startTitulo3 < 80){

						array_push($pdf->pages, $page);
						$page = new Zend_Pdf_Page(
						Zend_Pdf_Page::SIZE_A4);
						//letras de color negro
						$letraNegra = new Zend_Pdf_Style();
						$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
						$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
						$page->setStyle($letraNegra);

						$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
						$page->drawLine(10, 820, ($page->getWidth()-10), 820);
						$page->drawLine(10, 25, ($page->getWidth()-10), 25);

						$startTitulo3 = 800;

					}
					$page->setStyle($letraAzul);
					$page->drawText('TECNOLOGIAS POSIBLES ', 40, $startTitulo3);
					$page->drawText('________________________________________________________________________', 30, $startTitulo3-5);

					$php = $propu->getPhp($this->_getParam('idProposals'));
					$ajax = $propu->getAjax($this->_getParam('idProposals'));
					$mysql = $propu->getMysql($this->_getParam('idProposals'));
					$oracle = $propu->getOracle($this->_getParam('idProposals'));
					$java = $propu->getJava($this->_getParam('idProposals'));
					$jquey = $propu->getJquery($this->_getParam('idProposals'));
					$css = $propu->getCss($this->_getParam('idProposals'));
					$html = $propu->getHtml($this->_getParam('idProposals'));
					$jquery = $propu->getJquery($this->_getParam('idProposals'));

					$page->setStyle($letraNegra);
					$startTec = $startTitulo3;
					$startPhp = $startTitulo3-20;
					if($php == 1){

						$defPhp = 'PHP: Es un lenguaje de programación interpretado, diseñado originalmente para la creación de páginas web dinámicas. Se usa principalmente para la interpretación del lado del servidor (server-side scripting)';
						$cadePhp = strip_tags($defPhp);
						$cadePhp = wordwrap($cadePhp, 155, '\n');
						$lineasPhp = explode('\n', $cadePhp);
						foreach ($lineasPhp as $linePhp){

							if ($startPhp < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startPhp = 800;

							}

							$linePhp = ltrim($linePhp);
							$page->drawText($linePhp, 40, $startPhp);
							$startPhp = $startPhp -10;
						}
					}else{
						$startPhp = $startTitulo3;
					}
					$startJava = $startPhp-10;
					if($java == 1){

						$defJava = 'JAVA: Las aplicaciones Java pueden ejecutarse en cualquier máquina virtual Java (JVM), independientemente de la arquitectura de computadores. Java es basado en clases, lenguaje orientado a objetos que está diseñado para permitir a los desarrolladores de aplicaciones "escribir una vez, ejecutar en cualquier lugar."';
						$cadeJava = strip_tags($defJava);
						$cadeJava = wordwrap($cadeJava, 155, '\n');
						$lineasJava = explode('\n', $cadeJava);
						foreach ($lineasJava as $lineJava){

							if ($startJava < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startJava = 800;

							}

							$lineJava = ltrim($lineJava);
							$page->drawText($lineJava, 40, $startJava);
							$startJava = $startJava -10;
						}
					}else{
						$startJava = $startPhp-10;
					}
					$startMysql = $startJava-10;
					if($mysql == 1){

						$defMysql = 'MySQL: Es un sistema de gestión de bases de datos relacional, multihilo y multiusuario con más de seis millones de instalaciones.1 MySQL AB —desde enero de 2008 una subsidiaria de Sun Microsystems y ésta a su vez de Oracle Corporation desde abril de 2009— desarrolla MySQL como software libre en un esquema de licenciamiento dual.';
						$cadeMysql = strip_tags($defMysql);
						$cadeMysql = wordwrap($cadeMysql, 155, '\n');
						$lineasMysql = explode('\n', $cadeMysql);
						foreach ($lineasMysql as $lineMysql){

							if ($startMysql < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startMysql = 800;

							}

							$lineMysql = ltrim($lineMysql);
							$page->drawText($lineMysql, 40, $startMysql);
							$startMysql = $startMysql -10;
						}
					}else{
						$startMysql = $startJava-5;
					}
					$startOracle = $startMysql-10;
					if($oracle == 1){
						 
						$defOracle = 'ORACLE: Es un sistema de gestión de base de datos objeto-relacional (o ORDBMS por el acrónimo en inglés de Object-Relational Data Base Management System), desarrollado por Oracle Corporation.';
						$cadeOracle = strip_tags($defOracle);
						$cadeOracle = wordwrap($cadeOracle, 155, '\n');
						$lineasOracle = explode('\n', $cadeOracle);
						foreach ($lineasOracle as $lineOracle){

							if ($startOracle < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startOracle = 800;

							}

							$lineOracle = ltrim($lineOracle);
							$page->drawText($lineOracle, 40, $startOracle);
							$startOracle = $startOracle -10;
						}
					}else{
						$startOracle = $startMysql;
					}
					$startAjax = $startOracle-10;
					if($ajax == 1){

						$defAjax = 'AJAX: acrónimo de Asynchronous JavaScript And XML (JavaScript asíncrono y XML), es una técnica de desarrollo web para crear aplicaciones interactivas o RIA (Rich Internet Applications). Estas aplicaciones se ejecutan en el cliente, es decir, en el navegador de los usuarios mientras se mantiene la comunicación asíncrona con el servidor en segundo plano. ';
						$cadeAjax = strip_tags($defAjax);
						$cadeAjax = wordwrap($cadeAjax, 155, '\n');
						$lineasAjax = explode('\n', $cadeAjax);
						foreach ($lineasAjax as $lineAjax){

							if ($startAjax < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startAjax = 800;

							}

							$lineAjax = ltrim($lineAjax);
							$page->drawText($lineAjax, 40, $startAjax);
							$startAjax = $startAjax -10;
						}
					}else{
						$startAjax = $startOracle;
					}

					$startJquery = $startAjax-10;
					if($jquery == 1){

						$defJquery = 'JQuery: jQuery es una biblioteca de JavaScript, creada inicialmente por John Resig, que permite simplificar la manera de interactuar con los documentos HTML, manipular el árbol DOM, manejar eventos, desarrollar animaciones y agregar interacción con la técnica AJAX a páginas web.';
						$cadeJquery = strip_tags($defJquery);
						$cadeJquery = wordwrap($cadeJquery, 155, '\n');
						$lineasJquery = explode('\n', $cadeJquery);
						foreach ($lineasJquery as $lineJquery){

							if ($startJquery < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startJquery = 800;

							}

							$lineJquery = ltrim($lineJquery);
							$page->drawText($lineJquery, 40, $startJquery);
							$startJquery = $startJquery-10;
						}
					}else{
						$startJquery = $startAjax;
					}
					$startHtml = $startJquery-10;
					if($html == 1){
						 
						$defHtml = 'HTML5:  (HyperText Markup Language, versión 5) es la quinta revisión importante del lenguaje básico de la World Wide Web, HTML. HTML5 especifica dos variantes de sintaxis para HTML: un «clásico» HTML (text/html),';
						$cadeHtml = strip_tags($defHtml);
						$cadeHtml = wordwrap($cadeHtml, 155, '\n');
						$lineasHtml = explode('\n', $cadeHtml);
						foreach ($lineasHtml as $lineHtml){
							if ($startHtml < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startHtml = 800;

							}
							$lineHtml = ltrim($lineHtml);
							$page->drawText($lineHtml, 40, $startHtml);
							$startHtml = $startHtml -10;
						}
					}else{
						$startHtml = $startJquery;
					}
					$startCss = $startHtml-10;
					if($css == 1){

						$defCss = 'Css: Cascading Style Sheets (CSS) es un lenguaje de hojas de estilo se utiliza para describir la semántica de la presentación (el aspecto y el formato) de un documento escrito en un lenguaje de marcas';
						$cadeCss = strip_tags($defCss);
						$cadeCss = wordwrap($cadeCss, 155, '\n');
						$lineasCss = explode('\n', $cadeCss);
						foreach ($lineasCss as $lineCss){
							if ($startCss < 70){

								array_push($pdf->pages, $page);
								$page = new Zend_Pdf_Page(
								Zend_Pdf_Page::SIZE_A4);
								//letras de color negro
								$letraNegra = new Zend_Pdf_Style();
								$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
								$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
								$page->setStyle($letraNegra);

								$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
								$page->drawLine(10, 820, ($page->getWidth()-10), 820);
								$page->drawLine(10, 25, ($page->getWidth()-10), 25);

								$startCss = 800;

							}
							$lineCss = ltrim($lineCss);
							$page->drawText($lineCss, 40, $startCss);
							$startCss = $startCss -10;
						}
					}else{
						$startCss = $startHtml;
					}

					$startTitulo6 = $startCss-30;
					if ($startTitulo6 < 80){

						array_push($pdf->pages, $page);
						$page = new Zend_Pdf_Page(
						Zend_Pdf_Page::SIZE_A4);
						//letras de color negro
						$letraNegra = new Zend_Pdf_Style();
						$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
						$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
						$page->setStyle($letraNegra);

						$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
						$page->drawLine(10, 820, ($page->getWidth()-10), 820);
						$page->drawLine(10, 25, ($page->getWidth()-10), 25);

						$startTitulo6 = 800;

					}
					$page->setStyle($letraAzul);
					$page->drawText('Manifiesto Agil ', 40, $startTitulo6);
					$page->drawText('________________________________________________________________________', 30, $startTitulo6-5);
					$page->setStyle($letraNegra);
					$startMani = $startTitulo6-20;
					$defMani = 'Estamos poniendo al descubierto mejores métodos para desarrollar software,  haciéndolo y ayudando a otros a que lo hagan. Con este trabajo hemos llegado a valorar:';
					$defMani2 = 'A los individuos y su interacción, El software que funciona, La colaboración con el cliente, La respuesta al cambio,';
					$defMani3 = 'por encima de los procesos y las herramientas. por encima de la documentación exhaustiva. por encima de la negociación contractual. por encima del seguimiento de un plan.';

					$cadeMani = strip_tags($defMani);
					$cadeMani = wordwrap($cadeMani, 155, '\n');
					$lineasMani = explode('\n', $cadeMani);
					foreach ($lineasMani as $lineMani){
						if ($startMani < 70){

							array_push($pdf->pages, $page);
							$page = new Zend_Pdf_Page(
							Zend_Pdf_Page::SIZE_A4);
							//letras de color negro
							$letraNegra = new Zend_Pdf_Style();
							$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
							$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
							$page->setStyle($letraNegra);

							$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
							$page->drawLine(10, 820, ($page->getWidth()-10), 820);
							$page->drawLine(10, 25, ($page->getWidth()-10), 25);

							$startMani = 800;

						}
						$lineMani = ltrim($lineMani);
						$page->drawText($lineMani, 40, $startMani);
						$startMani = $startMani -10;
					}
					//imprime linea dos
					$startMani2 = $startMani-20;
					$cadeMani2 = nl2br($defMani2);
					$lineasMani2 = explode(',', $cadeMani2);
					foreach ($lineasMani2 as $lineMani2){
						if ($startMani2 < 70){

							array_push($pdf->pages, $page);
							$page = new Zend_Pdf_Page(
							Zend_Pdf_Page::SIZE_A4);
							//letras de color negro
							$letraNegra = new Zend_Pdf_Style();
							$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
							$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
							$page->setStyle($letraNegra);

							$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
							$page->drawLine(10, 820, ($page->getWidth()-10), 820);
							$page->drawLine(10, 25, ($page->getWidth()-10), 25);

							$startMani2 = 800;

						}
						$lineMani2 = ltrim($lineMani2);
						$page->drawText($lineMani2, 160, $startMani2);
						$startMani2 = $startMani2 -10;
					}
					$startMani3 = $startMani-20;
					//imprime linea 3
					$cadeMani3 = nl2br($defMani3);
					$lineasMani3 = explode('.', $cadeMani3);
					foreach ($lineasMani3 as $lineMani3){
						if ($startMani3 < 70){

							array_push($pdf->pages, $page);
							$page = new Zend_Pdf_Page(
							Zend_Pdf_Page::SIZE_A4);
							//letras de color negro
							$letraNegra = new Zend_Pdf_Style();
							$letraNegra->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
							$letraNegra->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 7);
							$page->setStyle($letraNegra);

							$page->setLineColor(new Zend_Pdf_Color_Rgb(0,10,10));
							$page->drawLine(10, 820, ($page->getWidth()-10), 820);
							$page->drawLine(10, 25, ($page->getWidth()-10), 25);

							$startMani3 = 800;

						}
						$lineMani3 = ltrim($lineMani3);
						$page->drawText($lineMani3, 300, $startMani3);
						$startMani3 = $startMani3 -10;
					}


					// add page to document
					array_push($pdf->pages, $page);
					header('Content-type: application/pdf');

					// save as file
					$pdf->save('Propuesta.pdf');
					print '<script type="text/javascript">alert("Popuesta en PDF creada con exito");</script>';

				}catch (Zend_Pdf_Exception $e) {
					die ('PDF error: ' . $e->getMessage());
					print '<script type="text/javascript">alert("no Correcto intentalo de nuevo");</script>';
				}catch (Exception $e) {
					die ('Application error: ' . $e->getMessage());
				}

			}
			$this->_redirect('/proposal/index');
		}else{
			$id = $this->_getParam('idProposals', 0);
			$prop = new proposalDao();
			$this->view->propuestaP2 = $prop->getPropuesta($id);
		}
	}


	/* :::::::::::::::::::::::::::::::::::::::::::::::::
	 *  ::::::::::: ppt action ::::::::::::::::::::::::::
	 */

	public function pptAction(){
		// Create new PHPPowerPoint object
		echo date('H:i:s') . " nuevo objeto PHPPowerPoint creado\n";
		$objPHPPowerPoint = new PHPPowerPoint();

		// Set properties
		echo date('H:i:s') . " propiedades\n";
		$objPHPPowerPoint->getProperties()->setCreator("IOFractal");
		$objPHPPowerPoint->getProperties()->setLastModifiedBy("usuario");
		$objPHPPowerPoint->getProperties()->setTitle("Office 2007 PPTX Document");
		$objPHPPowerPoint->getProperties()->setSubject("Office 2007 PPTX Document");
		$objPHPPowerPoint->getProperties()->setDescription("documento pptx de office 2007 creado con librerias php.");
		$objPHPPowerPoint->getProperties()->setKeywords("office 2007 openxml php");
		$objPHPPowerPoint->getProperties()->setCategory("propuestas");

		/**
		 * Creates a templated slide
		 *
		 * @param PHPPowerPoint $objPHPPowerPoint
		 * @return PHPPowerPoint_Slide
		 */
		function createTemplatedSlide(PHPPowerPoint $objPHPPowerPoint){
			// Create slide
			$slide = $objPHPPowerPoint->createSlide();

			// Add background image
			$shape = $slide->createDrawingShape();
			$shape->setName('Background');
			$shape->setDescription('Background');
			$shape->setPath('img/realdolmen_bg.jpg');
			$shape->setWidth(950);
			$shape->setHeight(720);
			$shape->setOffsetX(0);
			$shape->setOffsetY(0);

			// Add logo
			$shape = $slide->createDrawingShape();
			$shape->setName('PHPPowerPoint logo');
			$shape->setDescription('PHPPowerPoint logo');
			$shape->setPath('img/phppowerpoint_logo.gif');
			$shape->setHeight(40);
			$shape->setOffsetX(10);
			$shape->setOffsetY(720 - 10 - 40);

			$shape->getShadow()->setVisible(true);
			$shape->getShadow()->setDirection(45);
			$shape->getShadow()->setDistance(10);

			// Return slide
			return $slide;
		}
		// Remove first slide
		echo date('H:i:s') . " Remove first slide\n";
		$objPHPPowerPoint->removeSlideByIndex(0);

		// Create templated slide
		echo date('H:i:s') . " Create templated slide\n";
		$currentSlide = createTemplatedSlide($objPHPPowerPoint); // local function

		// Create a shape (text)
		echo date('H:i:s') . " Create a shape (rich text)\n";
		$shape = $currentSlide->createRichTextShape();
		$shape->setHeight(300);
		$shape->setWidth(600);
		$shape->setOffsetX(170);
		$shape->setOffsetY(180);
		$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER );
		$textRun = $shape->createTextRun('Gracias por usar PHPPowerPoint!');
		$textRun->getFont()->setBold(true);
		$textRun->getFont()->setSize(60);
		$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFC00000' ) );

		// Save PowerPoint 2007 file
		echo date('H:i:s') . " Write to PowerPoint2007 format\n";
		$objWriter = PHPPowerPoint_IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
		$objWriter->save(str_replace('.php', '.pptx', __FILE__));

		// Echo memory peak usage
		echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n";

		// Echo done
		echo date('H:i:s') . " Done writing file.\r\n";

	}

	public function addAction(){}

}

