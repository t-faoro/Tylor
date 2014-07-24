<?php
/**
 * Class: Layout
 * Date: May 5, 2013
 * 
 * Purpose: To create starter layouts for PHP websites, using an Object Oriented Method
 * @author Tylor Faoro 
 * 
 */ 	
	
class Layout{

public $content = array();
public $styleSheet = array();
public $javaScript = array();
public $page = "";
public $pageTitle = TITLE;
public $head = "";
public $body = "";
	

	public function setTitle($pageTitle){
		$this->pageTitle = TITLE." ".$pageTitle;
	}


	
	/**
	 * Adds CSS into the <HEAD> of the HTML page. The stylesheets are passed as an array, so all adds will be put
	 * into the site in its appropriate spot.
	 * @author Tylor Faoro
	 * 
	 * @param mixed $style The array of Stylesheets 
	 */
	public function addCSS($style){
		$this->styleSheet[] .= $style;
		
	}
	
	/**
	 * Adds Javascript into the <HEAD> of the HTML page. The Javascripts are passed as an array, so all adds will be put
	 * into the site in its appropriate spot.
	 * @author Tylor Faoro
	 * 
	 * @param mixed $js The array of Javascript 
	 */
	public function addJS($js){
		$this->javaScript[] .= $js;
	}
	
	/**
	 * Declares the DOCTYPE and <HTML> Tag at the top of the page.
	 * @author Tylor Faoro
	 * 
	 * @param none
	 * @return String $page The HTML Markup for the front end.
	 */
	public function setPage(){
		$page  = "<!DOCTYPE html>"."\n";	
		$page .= "<HTML>"."\n";
		
		return $page;
		
	}
	
	/**
	 * Declares the <HEAD> and <TITLE> sections of the website. This method will also place the array of javascript
	 * and CSS into the HEAD where it belongs.
	 * @author Tylor Faoro
	 * 
	 * @param String $title The Site's Title, declared as a Constant in config.php
	 * @return String $head The actual <HEAD></HEAD> Information is returned to the Front End.
	 */
	public function setHeader(/*$title*/){
		$javascript = "";
		$style = "";	
			
		if(is_array($this->styleSheet)){
			foreach($this->styleSheet as $value){
				$style .= '<link rel="stylesheet" type="text/css" href="'. CSS_PATH .$value.'">'."\n";
			}
		}
		if(is_array($this->javaScript)){
			foreach($this->javaScript as $value){
				$javascript .= '<script defer src="'. JS_PATH .$value.'"></script>'."\n";
			}
		}	
				
		$head  = "<head>"."\n";
		
		$head .= '<meta http-equiv="X-UA-Compatible" content="IE=9,chrome=1">';
		$head .= '<meta name="google-site-verification" content="wIV3tJ5Y5dIAay_dJbn9rcm2CzMC-xjvAATDJLERIiA" />';
		$head .= '<meta charset="utf-8">';
		$head .= '<meta http-equiv="X-UA-Compatible" content="IE=9" />';
		$head .= '<meta name="fb_admins_meta_tag" content="andrea.zdun">';
		$head .= '<meta name="keywords" content="AB, Alberta, Lethbridge, Southern, WPIC, Wedding, certified, coordinating, coordination, coordinator, event, events, planner, planning, styling, Taber, Coaldale, Coalhurst, Andrea Zdun, Andrea, Zdun, Wedding Planner, Event Planner, party, Crowsnest Pass, Gay Marriage, Gay Weddings, Elegant, Elegant Wedding, Elegant Weddings, Elegent Weddings & Events, Elegant Weddings and Events, hourly, packages, promotions, wedding checklist, wedding planning, how to plan a wedding, wedding planning checklist, lesbian, lesbian weddings, wedding planners, event planners, wedding events, wedding event planner, wedding and event planning, weddings, wedding consultant, event consultant, consultants">';
		$head .= '<meta name="description" content="Wedding and event planning and coordination service for Lethbridge and surrounding areas. Certified wedding planner dedicated to your event going smoothly. Let Elegant Weddings &amp; Events plan your dream event today.">';
		
		$head .= $style;
		$head .= $javascript;
		$head .= "<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>";
		/*$head .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>';
		$head .= '<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>';
		$head .= '<script src="javascript/allinone_bannerRotator.js" type="text/javascript"></script>';*/
		$head .= "<script type='text/javascript'>

				  var _gaq = _gaq || [];
				  _gaq.push(['_setAccount', 'UA-48874254-1']);
				  _gaq.push(['_setDomainName', 'elegantweddingsevents.com']);
				  _gaq.push(['_setAllowLinker', true]);
				  _gaq.push(['_trackPageview']);
				
				  (function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				  })();
				
				</script>";
		
		$head .= '<link rel="shortcut icon" type="image/x-icon" href="images/tabIcon.ico">';
		$head .= "\n".'<title>'.$this->pageTitle.'</title>'."\n";		
		$head .= "\n"."</head>"."\n";
		
		return $head;
	}
	
	/**
	 * Simply places an opening <BODY> tag on the Front End.
	 * @author Tylor Faoro
	 * 
	 * @return String $body The Opening Body Tag 
	 */
	public function openBody(){
		$body = "\n"."<body>"."\n";
		$body .= '<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, "script", "facebook-jssdk"));</script>';
		$body .= '<noscript><div id="noScript"><img src="img/error.png" alt="Javascript Disabled Error" class="icon" />Elegant Weddings &amp; Events is best viewed with Javascript On. To do this, please consult your browser\'s help files. Or, visit this <a class="noScript" href="http://enable-javascript.com/" alt="How to Enable Javascript" target="_blank">website</a> for instructions.</div></noscript>';
		
		return $body;
	}
	
	/**
	 * Simply places a closing </BODY> tag on the front end.
	 * @author Tylor Faoro
	 * 
	 * @return String $body The Closing Body Tag 
	 */
	public function closeBody(){
		$body = "\n"."</body>"."\n";
		
		return $body;
	}
	
	/**
	 * Places the closing </HTML> tag at the very bottom of the page. No more information may be placed after this tag.
	 * @author Tylor Faoro
	 * 
	 * @return String $page The ending HTML Tag. 
	 */
	public function endPage(){
		$page = "\n"."</html>";
		
		return $page;
	}
	


	 
	
}

?>