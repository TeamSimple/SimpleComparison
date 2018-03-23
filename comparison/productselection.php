<html>
<title>simpleComparison</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<head>
	<link rel="stylesheet" type="text/css" href="css/comparison.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
			<?php

			require_once('simple_html_dom.php');
			$string = str_replace(" ", "+", $_GET['website_url']);
			$website_url = 'https://www.idealo.co.uk/mscat.html?q='.$string.'&qr=false';
			$html = file_get_html($website_url);
			$idealo = 'https://www.idealo.co.uk';
			
			$images = array();
			$prices = array();
			$titles = array();
			$links = array();
			$hasOffers = array();
			$count = 0;
				
			?>

	<div id="mainContainer" style="margin:5% 10%;">
		<div class="w3-container">
			<div class="w3-container w3-round w3-grey">

				<div class="w3-row" style="padding:0.5%;">
					<div class="w3-col m8">
						<h2>Results for "<?php echo $_GET['website_url']?>"</h2>
					</div>

					<div class="w3-col m4  w3-center">
						<form class="w3-padding" style="bottom-border: none;" placeholder="Search Product" method="get" name="scrap_form" id="scrap_form" action="productselection.php">
							<input  class="w3-input2 w3-round" placeholder="Search Product" type="input" name="website_url" id="website_url" required>
							
							<button type="submit" class="w3-button w3-round w3-yellow w3-hover-white w3-border w3-border-yellow"><i class="fa fa-search "></i></button>
						</form>

					</div>
					</div>

				</div>

			</div>


		<?php
		if(($html->find('.no-result-topContainer'))) { //if no results are found 
			echo'
			<div class="w3-row-padding w3-margin-top">
				<div class="w3-col m12 w3-center">
				 	<h1> No results found, please try again. </h1>
				</div>
			</div>

			<div class="w3-row-padding w3-margin-top">
			<div class="w3-col m3">
			<h1></h1>
			</div>
			<div class="w3-col m6">
				 	<form style="border-bottom: none;" class="w3-input"  placeholder="Search Product" method="get" name="scrap_form" id="scrap_form" action="productselection.php">
							<input style="border-bottom: none;"class="w3-input" placeholder="Search Product" type="input" name="website_url" id="website_url">
							<input style="border-bottom: none;" class="w3-input w3-button w3-round w3-hover-grey w3-yellow" type="submit" name="submit" value="Search" >
						</form>
				</div>
				<div class="w3-col m3">
				<h1></h1>
				</div>
				
			</div>';
		}else{
			foreach($html->find('.offerList-item') as $element) 
			{			
				$count++;

				foreach($element->find('.offerList-item-image img') as $image)
				{
				//$images[] = '<img src="'.$image->src.'">'.'<br>';//$img->src;
				$images[] = $image->src;//$img->src;
				}


				foreach($element->find('.offerList-item-description-title') as $title)
				{
					$titles[] = $title->innertext;
				}	
				
				foreach($element->find('.offerList-item-priceWrapper') as $pricewrap){
					if(($pricewrap->find('.offerList-item-priceNoOffer', 0))) {
						foreach($pricewrap->find('.offerList-item-priceNoOffer')as $price){
							$prices[] = 'No offfers available';
						}
					}else if(($pricewrap->find('.price', 0))) {
						foreach($pricewrap->find('.price')as $price){
							$pound = $price->find('text',1);
							$number = $price->find('text',2).$price->find('text',3);
							$number2 = str_replace(' ', '', $number);
							$prices[] = $pound.''.$number2;
							$hasOffers[] = 0;
						}
					}else{
						foreach($element->find('.priceRange-from')as $price){
						$pound = $price->find('text',1);
						$number = $price->find('text',2);
						$prices[] = $pound.' '.$number;
						$hasOffers[] = 1;
						}
					}
				}

				 foreach($element->find('.offerList-itemWrapper') as $compare) //Get store links
				 {
					$links[] = $idealo.$compare->getAttribute('href');
				 }
			
			}
		}
		$size = sizeof($prices);
		for($i = 0;$i < $size; $i+=3){
			if($i+1 >= $size){
				echo'
			<div class="w3-row-padding w3-margin-top">

				<div class="w3-third">
					<div class="w3-card w3-center w3-padding w3-round">
						<img style="max-height:160px; max-width:160px;" src="'.$images[$i].'">
						<div class="w3-container w3-center" style="margin-bottom: 15px;">
							<h5>'.$titles[$i].'</h5>
							<p>'.$prices[$i].'</p>
							';if($hasOffers[$i] == 1){
								echo'
									<form method="get" name="show_form" id="show_form" action="show.php">
										<input id="compareId" name="compareId" type="hidden" value='.$links[$i].'>
										<input class="w3-button w3-round w3-hover-grey w3-yellow" style="width:70%"type="submit" value="Compare Prices">
									</form>'; 
							}else if($hasOffers[$i] == 0){
								echo'<a href="'.$links[$i].'"><button class="w3-button w3-round w3-hover-grey w3-yellow" 
							style="width:70%; text-decoration:none;">Go to Shop</button></a>';}
							echo'
						</div>
					</div>
				</div>
				
				</div>';
			}else if($i+2 >= $size){
				echo'
			<div class="w3-row-padding w3-margin-top">

				<div class="w3-third">
					<div class="w3-card w3-center w3-padding w3-round">
						<img class="product-image" src="'.$images[$i].'">
						<div class="w3-container w3-center" style="margin-bottom: 15px;">
							<h5>'.$titles[$i].'</h5>
							<p>'.$prices[$i].'</p>
							';if($hasOffers[$i] == 1){
								echo'
									<form method="get" name="show_form" id="show_form" action="show.php">
										<input id="compareId" name="compareId" type="hidden" value='.$links[$i].'>
										<input class="w3-button w3-round w3-hover-grey w3-yellow" style="width:70%"type="submit" value="Compare Prices">
									</form>'; 
							}else if($hasOffers[$i] == 0){
								echo'<a href="'.$links[$i].'"><button class="w3-button w3-round w3-hover-grey w3-yellow" 
							style="width:70%; text-decoration:none;">Go to Shop</button></a>';}
							echo'
						</div>
					</div>
				</div>

				<div class="w3-third">
					<div class="w3-card w3-center w3-padding w3-round">
						<img class="product-image"  src="'.$images[$i+1].'">
						<div class="w3-container w3-center"style="margin-bottom: 15px;">
							<h5>'.$titles[$i+1].'</h5>
							<p >'.$prices[$i+1].'</p>
							';if($hasOffers[$i+1] == 1){
								echo'
									<form method="get" name="show_form" id="show_form" action="show.php">
										<input id="compareId" name="compareId" type="hidden" value='.$links[$i+1].'>
										<input class="w3-button w3-round w3-hover-grey w3-yellow" style="width:70%"type="submit" value="Compare Prices">
									</form>'; 
							}else if($hasOffers[$i+1] == 0){
								echo'<a href="'.$links[$i+1].'"><button class="w3-button w3-round w3-hover-grey w3-yellow" 
							style="width:70%; text-decoration:none;">Go to Shop</button></a>';}
							echo'
						</div>
					</div>
				</div>
							</div>';
			
			}else{
			echo'
			<div class="w3-row-padding w3-margin-top">

				<div class="w3-third">
					<div class="w3-card w3-center w3-padding w3-round">
						<img class="product-image" src="'.$images[$i].'">
						<div class="w3-container w3-center" style="margin-bottom: 15px;">
							<h5>'.$titles[$i].'</h5>
							<p>'.$prices[$i].'</p>
							';if($hasOffers[$i] == 1){
								echo'
									<form method="get" name="show_form" id="show_form" action="show.php">
										<input id="compareId" name="compareId" type="hidden" value='.$links[$i].'>
										<input class="w3-button w3-round w3-hover-grey w3-yellow" style="width:70%"type="submit" value="Compare Prices">
									</form>'; 
							}else if($hasOffers[$i] == 0){
								echo'<a href="'.$links[$i].'"><button class="w3-button w3-round w3-hover-grey w3-yellow" 
							style="width:70%; text-decoration:none;">Go to Shop</button></a>';}
							echo'
						</div>
					</div>
				</div>

				<div class="w3-third">
					<div class="w3-card w3-center w3-padding w3-round">
						<img class="product-image"  src="'.$images[$i+1].'">
						<div class="w3-container w3-center"style="margin-bottom: 15px;">
							<h5>'.$titles[$i+1].'</h5>
							<p >'.$prices[$i+1].'</p>
							';if($hasOffers[$i+1] == 1){
								echo'
									<form method="get" name="show_form" id="show_form" action="show.php">
										<input id="compareId" name="compareId" type="hidden" value='.$links[$i+1].'>
										<input class="w3-button w3-round w3-hover-grey w3-yellow" style="width:70%"type="submit" value="Compare Prices">
									</form>'; 
							}else if($hasOffers[$i+1] == 0){
								echo'<a href="'.$links[$i+1].'"><button class="w3-button w3-round w3-hover-grey w3-yellow" 
							style="width:70%; text-decoration:none;">Go to Shop</button></a>';}
							echo'
						</div>
					</div>
				</div>
				
				<div class="w3-third">
					<div class="w3-card w3-center w3-padding w3-round">
						<img class="product-image"  src="'.$images[$i+2].'">
						<div class="w3-container w3-center" style="margin-bottom: 15px;">
							<h5>'.$titles[$i+2].'</h5>
							<p>'.$prices[$i+2].'</p>
							';if($hasOffers[$i+2] == 1){
								echo'
									<form method="get" name="show_form" id="show_form" action="show.php">
										<input id="compareId" name="compareId" type="hidden" value='.$links[$i+2].'>
										<input class="w3-button w3-round w3-hover-grey w3-yellow" style="width:70%"type="submit" value="Compare Prices">
									</form>'; 
							}else if($hasOffers[$i+2] == 0){
								echo'<a href="'.$links[$i+2].'"><button class="w3-button w3-round w3-hover-grey w3-yellow" 
							style="width:70%; text-decoration:none;">Go to Shop</button></a>';}
							echo'
						</div>
					</div>
				</div>


			</div>';
			}
		}
			?>
			<div class="simple-footer w3-round w3-grey" style="padding:0">
                          <div class="w3-left w3-padding" style="margin-top: 10px">Created by Team Simple for <a href="https://www.thisdadcan.co.uk/">This Dad Can</a>, powered by <a href="http://idealo.co.uk">Idealo</a></div>
                          <div class="w3-right w3-padding"><img src="https://i.imgur.com/PmywFd4.png" style="height:50px;"></div>
                        </div>
                    </div>
		</div>
		

	</body>
	</html>