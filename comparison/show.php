<html>
<title>simpleComparison</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<head>
	<link rel="stylesheet" type="text/css" href="css/comparison.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>


	<div id="mainContainer" style="margin:5% 10%;">

	<div class="w3-container">
			<div class="w3-container w3-round w3-grey">

				<div class="w3-row" style="padding:0.5%;">
					<div class="w3-col m9">
						<h1>Price Comparison</h1>
					</div>

					<div class="w3-col m3 w3-center w3-margin-top">
						 <button onclick="goBack()" class="w3-button w3-round w3-hover-white w3-yellow w3-border w3-border-yellow w3-margin-right">Back to Products</button>
					<script>
						function goBack() {
							window.history.back();
						}
					</script>

					</div>
					</div>

				</div>

			</div>


			<?php

			require_once('simple_html_dom.php');
			$website_url = $_GET['compareId'];
			$html = file_get_html($website_url); //the button that they clicked on related link 


				$images = array();
				$prices = array();
				$titles = array();
				$sellers = array();
				$links = array();
				$offers = array();
				$alt = array();
				$mainInfo[] = array();
				
				foreach($html->find('.oopStage-title') as $hold){ //Getting Page title 
					$mainTitle = $hold;
				}	
				foreach($html->find('.table-cell')as $info){
					$mainInfo[] = $info->innertext;
				}

				foreach($html->find('.rsImg') as $image){ //Get product image
						//$images[] = $image->src;//$img->src;
						$images[] = $image->getAttribute('href');;
						//echo $image;
				}			


				foreach($html->find('.productOffers-list') as $offer) 
				{
					$url = "https://www.idealo.co.uk";
					
					foreach($offer->find('.productOffers-listItemTitleInner') as $title) //Get product names
					{
						$titles[] = $title->innertext;
						//echo $title;
					}
					 foreach($offer->find('.productOffers-listItemOfferLogoShop') as $seller){ //Get seller image .productOffers-listItemOfferLogoShop'
					 $seller2 = $seller->getAttribute('data-shop-logo');
					 $sellers[] =$seller->getAttribute('data-shop-logo');
        			
					 $alt[] =$seller->getAttribute('alt', 1);
					 }
					foreach($offer->find('.productOffers-listItemOfferPrice') as $price) //Get prices 
					{
						$prices[] = $price->innertext;
						//echo $price;
					}
					foreach($offer->find('.productOffers-listItemOfferCtaLeadout') as $link) //Get store links
					{
						preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $link, $result);

						if (!empty($result)) {
								# Found a link.
								//echo $result['href'][0];
							$links[] = $result['href'][0];
						}
					}
				}	
				
				$size = sizeof($prices);
				$sellerCount=0;

				echo'
					<div class="w3-row" style="padding:0.5%;">
						<div class="w3-col m3  w3-center">
							<img style="width:250px;" src='.$images[0].'>
							
						</div>
						<div class="w3-col m9">
							<h1 style="padding: 30px 0px;">'.$mainTitle.'</h1>
							<h3 ">'.$mainInfo[1].'</h3>
							<h3 ">'.$mainInfo[2].'</h3>
						</div>
					</div>
					';
				
				for($i = 0; $i < $size; $i++){
					
					echo'
					<div class="w3-container w3-round w3-border w3-border-yellow w3-padding" style="margin:2%;" >

						<div class="w3-row" style="padding:0.5%;">

							<div class="w3-col m2 w3-center">
								<img src="'.$sellers[$sellerCount].'">
							</div>
							<div class="w3-col m6 w3-center ">
								 <!-- <h1>'.$hold.'</h1> -->
								 <h2>'.$alt[$sellerCount].'</h2>
							</div>
							<div class="w3-col m2  w3-center">
								<h1>'.$prices[$i].'</h1>
						
							</div>
							<div class="w3-col m2  ">
								<a href="'.$url.$links[$i].' " style="margin:2%; text-decoration:none;"><button class="w3-button w3-round w3-hover-white w3-block w3-yellow w3-hover-grey w3-border w3-border-yellow">Buy!</button></a>
							</div>
							

							

						</div>
					</div>';
					$sellerCount = $sellerCount + 2;	
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