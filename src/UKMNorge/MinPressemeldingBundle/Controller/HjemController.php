<?php

namespace UKMNorge\MinPressemeldingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use fylker as fylker;
use aviser as aviser;
use avis as avis;
use kommune as kommune;
use SQL as SQL;
use monstring as monstring;
use monstring_v2 as monstring_v2;
use innslag_v2 as innslag_v2;
use innslag_collection as innslag_collection;
use program as program;
use forestilling_v2 as forestilling_v2;
use forestillinger as forestillinger;

class HjemController extends Controller
{
    public function oversiktAction($id)
    {
	    require_once('UKM/avis.class.php');
	    require_once('UKM/monstringer.class.php');
	    require_once('UKM/monstring.class.php');
	    require_once('UKM/innslag.class.php');
	    require_once('UKM/forestillinger.collection.php');
	    require_once('UKM/forestilling.class.php');
	    
	    $TWIG = array();

	    $TWIG['avis'] = new avis( $id );
	    
	    
	   	$season = UKM_HOSTNAME == 'ukm.dev' ? 2014 : date('Y');
	   	
	    $festivalen = new \landsmonstring( $season );
	    $festivalpl = $festivalen->monstring_get();
	    $TWIG['monstring'] = new monstring_v2( $festivalpl->get('pl_id') );
	    
	    $nedslagsfelt = $TWIG['avis']->getNedslagsfeltAsCSV();
	    if( UKM_HOSTNAME == 'ukm.dev' ) {
		    $nedslagsfelt[] = 2100;
		    $nedslagsfelt[] = 2101;
	    }
	    $pameldte = $TWIG['monstring']->getInnslag()->getAll();
	    foreach( $pameldte as $innslag ) {
		    if( !in_array( $innslag->getKommune()->getId(), $nedslagsfelt ) ) {
			    continue;
			}
			
			$TWIG['mine_innslag'][] = $innslag;
		}
		
   		$TWIG['mediegrupper'] = array('land'=>'UKM-festivalen', 'fylke'=>'Fylkesfestival', 'kommune'=>'Lokalmønstring');

        return $this->render('MinPRBundle:Hjem:oversikt.html.twig', $TWIG);
    }
}
