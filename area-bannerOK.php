<?php
define('MAGENTO_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('STORE_ID', 1);
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
if (!file_exists($mageFilename)) {
    echo $mageFilename." was not found";
    exit;
}
require_once $mageFilename;

Mage::app()->setCurrentStore(STORE_ID);
Mage::app()->loadArea('frontend');
$layout = Mage::getSingleton('core/layout');

//load default xml layout handle and generate blocks
$layout->getUpdate()->load('default'); 
$layout->generateXml()->generateBlocks();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php echo $layout->getBlock('head')->toHtml() ?>
<script src="http://www.agendadodia.com.br/js/lib/jquery/jquery-1.10.2.min.js"></script>
<style>

</style>


<link rel="stylesheet" href="bannerrev/bannerrevo/stylebanner.css">
</head>
<body>
<?php echo $layout->getBlock('after_body_start')->toHtml() ?>
<?php echo $layout->getBlock('global_notices')->toHtml() ?>
<?php echo $layout->getBlock('header')->toHtml() ?>
<div class="content-wrapper">
<?php

function SubtraiDia($data,$qtdd) {
       if (strstr($data, "-")){//verifica se tem a barra /
           $d = explode ("-", $data);//tira a barra
		   $vDia=$d[2];
		   $vDia=$vDia-$qtdd;
           $rstData = "$d[0]-$d[1]-$vDia";//separa as datas $d[2] = ano $d[1] = mes etc...
           return $rstData;
       }
}

$date = date('H:i:s');
$vHora = strtotime ( '-3 hour' , strtotime ( $date ) ) ;
$vHora = date ( 'H:i:s' , $vHora );


//$vHora = date('H:i:s');
$currentTimestamp = time();

//magento
$vDataHoje = date('Y-m-d', $currentTimestamp);

//$vDataHoje = SubtraiDia($vDataHoje,1);
echo 'hoje ' . $vDataHoje. "<br />";
echo 'agora ' . $vHora . "<br />";

$vQuerySelData = "SELECT * FROM aa_banners WHERE status=0 AND datainicio <='$vDataHoje' AND datafim >= '$vDataHoje' ORDER BY ordem ASC";
$vQuerySelDataImg = "SELECT * FROM aa_bannersimagem WHERE status=0 AND datainicio <='$vDataHoje' AND datafim >= '$vDataHoje' ORDER BY ordem ASC";
$pdo= new PDO( 
    'mysql:host=mysql.agendadodia.com.br;dbname=agendadodia01', 
    'agendadodia01',
    'jorge2468', 
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
); 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(!$pdo){
       die('Erro ao criar a conexão');
   } 			   
?>
</div>
<section id="banner" style=" -webkit-box-shadow: 0 1px 1px #888888;-moz-box-shadow: 0 1px 1px #888888;box-shadow: 0 1px 1px #888888;">
		<div class="banner-container">
			<div class="banner home-v1">
				<ul>


<?php $listaBannersImg = $pdo->query($vQuerySelDataImg)->fetchAll();

foreach ($listaBannersImg as $reg){
		$vExibir=true;
		// Se a DataInicial for MENOR que a DataHoje, não precisa checar a HoraInicio, caso contrário verificar hora.
		$vDataInicioBanner = $reg['datainicio'];
		$diffini = strtotime($vDataHoje) - strtotime($vDataInicioBanner);
		if ($diffini>0) {
			// Não é necessário checar hora	pois a DatadeHoje é maior que a data de início
		} else {
			// Reprova se Hora Início é MAIOR que Hora Atual
		$vHoraInicio = $reg['horainicio'];
		if( strtotime($vHoraInicio)>=strtotime($vHora)) {
		$vExibir=false;	
		}
		}
		
		//Se a Paralização da Hora Final diária estiver ativada faça a checagem. Se a Hora for reprovada, envie o comunicado para a anular a próxima rotina
		//$HoraFinalDiaria = $reg['horafinaldiaria'];
		//$HoraFinalDiaria=1;
		//if ($HoraFinalDiaria==1) {
			//if( strtotime($vHoraFim)<strtotime($vHora)) {
			//$vExibir=true;
			//$vAnulaProxRot = false;
			//} else {
			//$vExibir=false;
			//$vAnulaProxRot = true;
			//}
		//}
		
		
		// Se a DataFinal for MAIOR que a DataHoje, não precisa checar a HoraFinal, caso contrário verificar hora.
		
		
		$vDataLimiteBanner = $reg['datafim'];
		$diffinal = strtotime($vDataLimiteBanner) - strtotime($vDataHoje);
		if ($diffinal>0) {
		// Não é necessário checar hora		
		} else {
			$vHoraFim = $reg['horafim'];
			if( strtotime($vHoraFim)<strtotime($vHora)) {
		$vExibir=false;	
		}
		}
	
		//Se Paralização da Hora Final diária estiver ativada será refeito toda a checagem da hora final independente do dia
		$HoraFinalDiaria = $reg['tipohorafim'];
		//$HoraFinalDiaria=1;
		if ($HoraFinalDiaria==1) {
			$vHoraFim = $reg['horafim'];
		//echo "aaa" . ' ' . $reg['idproduto'] . "<br />";
			//echo "difini" . ' ' . $diffini . "<br />";
		//echo "bbb" . ' ' . strtotime($vHoraFim) . "<br />";
		//	echo "ccc" . ' ' . strtotime($vHora) . "<br />";
			if( strtotime($vHoraFim)<strtotime($vHora)) {
				$vExibir=false;
			} 
		}
		
		if ($vExibir) {

 ?>

					<li 
						class="slider-1" 
						data-transition="easeOutExpo" 
						data-slotamount="3" 
						data-thumb="http://www.agendadodia.com.br/bannerrev/img/slides/promocao.jpg"
						data-title="Promoção">
						
						<img 
							src="http://www.agendadodia.com.br/media/<?php echo $reg['imagem']; ?>" 
							data-bgrepeat="no-repeat" 
							data-bgfit="cover" 
							data-bgposition="top center"
							alt="Promoção">
						
						<!-- .banner-txt -->
						
                        
                        
                        
                        
                        
                         <!-- /.banner-txt -->

						<!-- .banner-form -->
						 <!-- /.banner-form -->
					</li>
                    
                    <?php }}?>







<?php

$listaBanners = $pdo->query($vQuerySelData)->fetchAll();
	foreach ($listaBanners as $reg){
		$vExibir=true;
		// Se a DataInicial for MENOR que a DataHoje, não precisa checar a HoraInicio, caso contrário verificar hora.
		$vDataInicioBanner = $reg['datainicio'];
		$diffini = strtotime($vDataHoje) - strtotime($vDataInicioBanner);
		if ($diffini>0) {
			// Não é necessário checar hora	pois a DatadeHoje é maior que a data de início
		} else {
			// Reprova se Hora Início é MAIOR que Hora Atual
		$vHoraInicio = $reg['horainicio'];
		if( strtotime($vHoraInicio)>=strtotime($vHora)) {
		$vExibir=false;	
		}
		}
		
		//Se a Paralização da Hora Final diária estiver ativada faça a checagem. Se a Hora for reprovada, envie o comunicado para a anular a próxima rotina
		//$HoraFinalDiaria = $reg['horafinaldiaria'];
		//$HoraFinalDiaria=1;
		//if ($HoraFinalDiaria==1) {
			//if( strtotime($vHoraFim)<strtotime($vHora)) {
			//$vExibir=true;
			//$vAnulaProxRot = false;
			//} else {
			//$vExibir=false;
			//$vAnulaProxRot = true;
			//}
		//}
		
		
		// Se a DataFinal for MAIOR que a DataHoje, não precisa checar a HoraFinal, caso contrário verificar hora.
		
		
		$vDataLimiteBanner = $reg['datafim'];
		$diffinal = strtotime($vDataLimiteBanner) - strtotime($vDataHoje);
		if ($diffinal>0) {
		// Não é necessário checar hora		
		} else {
			$vHoraFim = $reg['horafim'];
			if( strtotime($vHoraFim)<strtotime($vHora)) {
		$vExibir=false;	
		}
		}
	
		//Se Paralização da Hora Final diária estiver ativada será refeito toda a checagem da hora final independente do dia
		$HoraFinalDiaria = $reg['tipohorafim'];
		//$HoraFinalDiaria=1;
		if ($HoraFinalDiaria==1) {
			$vHoraFim = $reg['horafim'];
		//echo "aaa" . ' ' . $reg['idproduto'] . "<br />";
			//echo "difini" . ' ' . $diffini . "<br />";
		//echo "bbb" . ' ' . strtotime($vHoraFim) . "<br />";
		//	echo "ccc" . ' ' . strtotime($vHora) . "<br />";
			if( strtotime($vHoraFim)<strtotime($vHora)) {
				$vExibir=false;
			} 
		}
		
		if ($vExibir) {
			
					$obj = Mage::getModel('catalog/product');
	$UrlProduto = "#";
	$product_id = $reg['idproduto'];
	$_product = $obj->load($product_id); // Enter your Product Id in $product_id
	$productowner=Mage::getModel('marketplace/product')->isCustomerProduct($product_id); 
	$IdLoja = $productowner['userid'];
	//echo 'idloja' . $IdLoja;
	$rowsocial=Mage::getModel('marketplace/userprofile')->getPartnerProfileById($IdLoja);
	$logo = $rowsocial['logopic'];
	$shoptitle = $rowsocial['shoptitle'];
	$lojaURL = $rowsocial['profile_request_url'];
	
	$buscaUrl = $pdo -> prepare( "select profileurl from marketplace_userdata WHERE mageuserid = $IdLoja" );

$buscaUrl -> execute();
$resultado = $buscaUrl -> fetch();
$NomePerfil = $resultado["profileurl"];
$linkPerfil = 'marketplace/seller/profile/' . $NomePerfil;
$vQuery3 = "SELECT * FROM core_url_rewrite WHERE target_path = '$linkPerfil'";
//echo 'sql' . $vQuery3;
$listaUrls = $pdo->query($vQuery3 )->fetchAll();
foreach ($listaUrls as $reg){
	$linkUrlFinal = $reg['request_path'];
}
if ($linkUrlFinal=='') {
	$linkUrlFinal = $linkPerfil;
}



//echo '---------' . $product_id . '--------------';
//echo  $productowner['userid'];
//echo 'titulo loja  ' . $shoptitle;
//echo 'url loja  ' . $rowsocial['profileurl'];
// get Product's name
//echo $_product->getName();
//get product's short description
//echo $_product->getShortDescription();
//get Product's Regular Price
$vPreco = $_product->getPrice();
$vPreco = number_format($vPreco, 2, ',', '.');
//echo $_product->getPrice();

//get Product's Url
$UrlProduto = $_product->getProductUrl();
			
			
			?>
            
              <li 
						class="slider-2" 
						data-transition="easeOutExpo" 
						data-slotamount="3" 
						data-thumb="http://www.agendadodia.com.br/bannerrev/img/slides/bolsatassi.jpg"
						data-title="Bolsa Tassi">
						
						<img 
							src="http://www.agendadodia.com.br/bannerrev/img/slides/bg1.jpg" 
							data-bgrepeat="no-repeat" 
							data-bgfit="cover" 
							data-bgposition="top center"
							alt="Bolsa Tassi">
						
						<!-- .banner-txt -->
						<div 
							class="caption zoomin banner-txt col-lg-5 tp-resizeme" 
							data-x="570" 
							data-y="40" 
							data-speed="700" 
							data-start="1700"  
							data-easing="Power3.easeInOut"
                            data-splitin="none"
                            data-endspeed="1000">

							<span class="titulo"><?php echo $_product->getName(); ?></span><br />
							<span class="subtitulo"><?php echo $_product->getShortDescription(); ?></span>
                            <div class="divtab" style="margin-top:7px;"><table><tr class="linhasup"><td></td><td class="barravermelha"><div style="height:5px; background-color:#e50014"></div></td></tr><tr><td><span class="comprepor">Compre <br />por</span> </td><td><span style="color:#e50014;font-family: 'Raleway', sans-serif;">R$</span> <span class="preco"><?php echo $vPreco; ?></span></td></tr>
                            <tr class="linhavermelhainf"><td></td><td class="barravermelha"><div style="height:5px; background-color:#e50014"></div></td></tr>
                            <tr class="linhavenda"><td style="padding-top:7px;"><span class="vendidopor">Vendido por</span></td><td class="vendedor" ><a href="<?php echo $linkUrlFinal; ?> "><img style="width:80px;height:auto;" src="http://www.agendadodia.com.br/media/avatar/<?php echo $logo; ?>"></a></td></tr>
                            
                            </table></div>
                             <span class="preco2"><?php echo $vPreco; ?></span>
                            
					    </div>
                        
                        <div 
							class="caption zoomin banner-txt col-lg-5 tp-resizeme" 
							data-x="300" 
							data-y="0" 
							data-speed="700" 
							data-start="1700"  
							data-easing="Power4.easeOut"
                            data-endspeed="2000">

							
							<p style="color:#363480;"><a href="<?php echo $UrlProduto; ?>"><img src="<?php echo $_product->getImageUrl(); ?>"  alt=""/></a></p>
							</div>
                        
                        
                        
                         <!-- /.banner-txt -->

						<!-- .banner-form -->
						 <!-- /.banner-form -->
					</li>
            
            
            <?php
					}}

?>

</ul>
			</div>
		</div>
	</section>
<?php echo $layout->getBlock('footer')->toHtml() ?>
<script src="bannerrev/bannerrevo/jquery.min.js"></script> <!-- jQuery JS -->
	<script src="bannerrev/bannerrevo/jquery.themepunch.tools.min.js"></script> <!-- Revolution Slider Tools -->
	<script src="bannerrev/bannerrevo/jquery.themepunch.revolution.min.js"></script> <!-- Revolution Slider -->
	<script src="bannerrev/bannerrevo/custombanner.js"></script> <!-- Custom JS -->

<?php
$pdo = null;
?>
</body>
</html>