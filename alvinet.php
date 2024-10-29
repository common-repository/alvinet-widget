<?php
/*
Plugin Name: Alvinet Widget
Plugin URI: https://www.alvinet.com/webmasters/widget.html
Description: Lecteur de flux RSS avancé. Affiche toute l'actualité francophone sur votre site : Politique, économie, faits divers, multimédia, culture... Sélectionnez simplement l'actu qui vous intéresse. Propulsé par Alvinet.
Author: soft4web
Tested up to: 5.1.1
Version: 2.0.0
Author URI: http://	www.soft4web.fr
*/


$instance = array("","","","","","","");

add_action('widgets_init', 'register_alvinet_widget');
function register_alvinet_widget() {
    register_widget('alvinet_widget');
}
/*class*/
class alvinet_widget extends WP_widget{

/*	function alvinet_widget(){
        
        $options = array(
			'classname' => 'alvinet-widget',
			'description' => 'Lecteur de flux RSS avancé. Affiche toute l\'actualité francophone sur votre site : Politique, économie, faits divers, multimédia, culture... Sélectionnez simplement l\'actu qui vous intéresse. Propulsé par Alvinet.'
			
		);
		$this->WP_widget('widget-alvinet','Alvinet Widget',$options);
	}*/
    public function __construct()
    {
        $options = array(
			'classname' => 'alvinet-widget',
			'description' => 'Lecteur de flux RSS avancé. Affiche toute l\'actualité francophone sur votre site : Politique, économie, faits divers, multimédia, culture... Sélectionnez simplement l\'actu qui vous intéresse. Propulsé par Alvinet.'
			
		);

        parent::__construct('alvinet-widget', 'Alvinet Widget', $options);

    }    
    function widget($args, $instance){
    
		?>	
		<script>
			
			function sendClickTrackToAlvinet(guid){
				(new Image()).src="https://www.alvinet.com/clktrk/id-"+guid; 
			};
			
		</script>
		<?php
        //recupere variable
        extract($args);
        
        
        echo $before_widget;

       	echo $before_title.$instance['titre'].$after_title;

				$items=0;
				//recuperation des variables utilisateur
				$limit = $instance['limit'];
				if($limit==0) return;
				//
				$keyword = trim($instance['keyword']);
				$akeyword 	= trim($instance['akeyword']);
				$catid  	= trim($instance['catid']);
				$depuis  	= trim($instance['depuis']);
				$or = trim($instance['or']);
				$url = 'http'.((isset($_SERVER['HTTPS']))?(($_SERVER['HTTPS']=='on')?'s':''):'').'://'.$_SERVER['HTTP_HOST'].(($_SERVER['SERVER_PORT']!='80')?':'.$_SERVER['SERVER_PORT']:'').$_SERVER['REQUEST_URI'];//url de la page affichant le module

				//récuperation du flux en consequence
				$rss = new DOMDocument();
				$rssUrl = 'https://alvinet.com/widget/?nbr='.$limit.
						'&keyword='.$keyword.
						'&akeyword='.$akeyword.
						'&catid='.$catid.
						'&periode=depuis&depuis='.$depuis.
						'&or='.$or.
						'&url='.$url.
						'&v=1.2.0';

				$htmlUrl = 'https://alvinet.com/actualite/?keyword='.$keyword.
						'&akeyword='.$akeyword.
						'&catid='.$catid.
						'&periode=depuis&depuis='.$depuis.
						'&or='.$or.'';

				$rss->load($rssUrl);


				$feed = array();
				foreach ($rss->getElementsByTagName('item') as $node) {
					$item = array ( 
						'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
						'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
						'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
						'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
						'guid' => $node->getElementsByTagName('guid')->item(0)->nodeValue,
						'source' => $node->getElementsByTagName('source')->item(0)->nodeValue
					);
					array_push($feed, $item);
					$items++;
				}

				
			/***DISPLAY***/
			
				//feed
				$counter = 0;
				for($x=0;$x<$items;$x++) {

						$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
						$link = $feed[$x]['link'];
						$description = $feed[$x]['desc'];
						$date = $feed[$x]['date'];
						$guid = $feed[$x]['guid'];
						$source = $feed[$x]['source'];

						If($title != ''){
		
							echo '<div class="alvinet_item" style="padding: 8px 0 8px">';
							echo '<div class="alvinet_title"><a  href="'.$link.'" onClick="sendClickTrackToAlvinet('.$guid.')" target="_blank" title="Lire cet article sur le site '.$source.'">'.$title.'</a></div>';
							echo '<div class="alvinet_description">'.$description.'</div>';
							echo '<div class="alvinet_source"><a  href="'.$link.'" onclick="sendClickTrackToAlvinet('.$guid.')" target="_blank" title="Lire cet article sur le site '.$source.'">'.$source.'</a>, <small>'.$date.'</small></div>';
							echo '</div>';

							$counter = $counter + 1;
						}				
				}

				if($counter==0) return;
		
				//logo agregateur
				$alvinet_widget_place = PLUGINDIR.'/'.dirname(plugin_basename(__FILE__));
				$alvinet_widget_logo = get_bloginfo( 'wpurl' ).'/'.$alvinet_widget_place.'/logo_alvinet.png';
				if($keyword!=""){/*mots clefs*/
					echo '<div style="text-align:right; padding: 0 0 4px;"><a href="'.$htmlUrl.'" target="_blank" title="Lire plus d\'actualités sur Alvinet : '.$keyword.'"><img src="'.$alvinet_widget_logo.'" alt="Alvinet"/></a></div>';
				}
				else {/*pas de mots clefs*/
					echo '<div style="text-align:right; padding: 0 0 4px;"><a href="'.$htmlUrl.'" target="_blank" title="Lire plus d\'actualités sur Alvinet"><img src="'.$alvinet_widget_logo.'" alt="Alvinet"/></a></div>';
				}
				//logo agregateur
			?>


        	
        	<?php
		
        	echo $after_widget;
	}
	
	
	
	
	/*************** PARAM ***********************/
	/*************** PARAM ***********************/
	/*************** PARAM ***********************/
	/*************** PARAM ***********************/
	/*************** PARAM ***********************/
	/*************** PARAM ***********************/
	/*************** PARAM ***********************/
	/*************** PARAM ***********************/
	
	
	
	/*formulaire param*/
	function form($instance){
    
        $default = array(
			"titre" => "Alvinet Widget",			
            "limit" => "5",			
            "keyword" => "",
            "akeyword" => "",
			"catid" => "0",
			"depuis" => "0",
			"or" => "0",
		);        
        
        $instance = wp_parse_args($instance, $default);
        
        
        ?>           

			
			
        <p>
            <label for="<?php echo $this->get_field_id("titre");?>"><b>Titre</b> </label><br/>
            <input name="<?php echo $this->get_field_name("titre");?>" 
                value="<?php echo $instance["titre"]; ?>"
                id="<?php echo $this->get_field_id("titre"); ?>"
                type="text"/>
        </p>
        
		<p>
            <label for="<?php echo $this->get_field_id("limit");?>"><b>Nombre d'actualités (max. 10)</b></label><br/>
            <input name="<?php echo $this->get_field_name("limit");?>" 
                value="<?php echo $instance["limit"]; ?>"
                id="<?php echo $this->get_field_id("limit"); ?>"
                type="text"/>
        </p>

		<!-- mots clefs -->
		<p>
            <label for="<?php echo $this->get_field_id("keyword");?>"><b>Mots clefs (séparés par un espace)</b></label><br/>
            <input name="<?php echo $this->get_field_name("keyword");?>" 
                value="<?php echo $instance["keyword"]; ?>"
                id="<?php echo $this->get_field_id("keyword"); ?>"
                type="text"/>
        </p>
		<p>
            <label for="<?php echo $this->get_field_id("akeyword");?>"><b>Mots clefs à exclure</b></label><br/>
            <input name="<?php echo $this->get_field_name("akeyword");?>" 
                value="<?php echo $instance["akeyword"]; ?>"
                id="<?php echo $this->get_field_id("akeyword"); ?>"
                type="text"/>
        </p>
       
 		<!--categories-->
        <p>
			<label for="<?php echo $this->get_field_id("catid");?>"><b>Catégorie</b></label><br/>
       		<select name="<?php echo $this->get_field_name("catid");?>" id="<?php echo $this->get_field_id("catid"); ?>"size="1">
				<option value="">Toutes les catégories</option>
				<option value="une">A la une</option>
				<option value="1">France</option>
				<option value="2">Europe</option>
				<option value="3">International</option>
				<option value="4">Politique</option>
				<option value="5">Economie</option>
				<option value="6">Culture</option>
				<option value="7">Médias et people</option>
				<option value="8">Multimédia</option>
				<option value="9">Sciences et médecine</option>
				<option value="44">Opinions et humeurs</option>
				<option value="45">Institutions</option>
				<option value="50">Organisations</option>
				<optgroup label="Sport">
					<option value="11">Athlétisme</option>
					<option value="12">Auto Moto</option>
					<option value="46">Boxe</option>
					<option value="13">Basket Hand Volley</option>
					<option value="14">Cyclisme</option>
					<option value="49">Etats-Unis</option>
					<option value="15">Football</option>
					<option value="47">Golf</option>
					<option value="48">Natation</option>
					<option value="16">Rugby</option>
					<option value="17">Sports d'hiver</option>
					<option value="18">Tennis</option>
					<option value="19">Voile</option>
					<option value="10">Tous les sports</option>
				</optgroup>
				<optgroup label="Régions">
					<option value="21">Alsace</option>
					<option value="22">Aquitaine</option>
					<option value="23">Auvergne</option>
					<option value="24">Basse-Normandie</option>
					<option value="25">Bourgogne</option>
					<option value="26">Bretagne</option>
					<option value="27">Centre</option>
					<option value="28">Champagne-Ardenne</option>
					<option value="29">Corse</option>
					<option value="30">Franche-Comté</option>
					<option value="52">Guadeloupe</option>
					<option value="53">Guyane</option>
					<option value="31">Haute-Normandie</option>
					<option value="32">Ile-de-France</option>
					<option value="38">La Réunion</option>
					<option value="33">Languedoc-Roussillon</option>
					<option value="34">Limousin</option>
					<option value="35">Lorraine</option>
					<option value="51">Martinique</option>
					<option value="54">Mayotte</option>
					<option value="36">Midi-Pyrénées</option>
					<option value="37">Nord-Pas-de-Calais</option>
					<option value="38">Outre-Mer</option>
					<option value="39">Pays de la Loire</option>
					<option value="40">Picardie</option>
					<option value="41">Poitou-Charentes</option>
					<option value="42">Provence-Alpes-Côte d'Azur</option>
					<option value="43">Rhône-Alpes</option>
					<option value="55">COM</option>
					<option value="20">Toutes les régions</option>   
				</optgroup>
			</select>
        </p>

        
		<!--période-->
        <p>
			<label for="<?php echo $this->get_field_id("depuis");?>"><b>Période</b></label><br/>
       		<select name="<?php echo $this->get_field_name("depuis");?>" id="<?php echo $this->get_field_id("depuis"); ?>"size="1">
				<option value="1">1 heure</option>
				<option value="12">12 heures</option>
				<option value="24">24 heures</option>
				<option value="48">48 heures</option>
				<option value="168">7 jours</option>
				<option value="720">1 mois</option>
				<option value="2160">3 mois</option>
				<option selected="selected" value="">5 mois</option>
			</select>
		</p>

        <!--options-->
        <p>
       		<label for="<?php echo $this->get_field_id("or");?>"><b>Mode de tri</b></label><br/>
       		<select name="<?php echo $this->get_field_name("or");?>" id="<?php echo $this->get_field_id("or"); ?>"size="1">
				<option selected="selected" value="date">date</option>
				<option value="perti">pertinence</option>
				<option value="popu">popularité</option>
			</select>
		</p>
        
        <?php


    }
	
	/*update - onSubmit*/
	function update($new,$old){
        
        /*ici traitements*/
        
        return $new;
    }
	
}
?>