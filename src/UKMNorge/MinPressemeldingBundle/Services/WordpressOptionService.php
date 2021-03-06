<?php
namespace UKMNorge\MinPressemeldingBundle\Services;
use UKMNorge\Wordpress\Outside\Option;
use Exception;

require_once('UKMconfig.inc.php');
require_once('UKM/Autoloader.php');


class WordpressOptionService
{
	var $pl_id = false;
	var $path = false;
	
	public function __construct() {
	}
	
	public function setMonstring( $pl_id, $path ) {
		$this->pl_id = $pl_id;
		$this->path = $path;
	}
	
	public function getOption( $key ) {
		if( false === $this->pl_id || false === $this->path ) {
			throw new Exception('WordpressOptionService: getOption krever at setMonstring er kjørt først');
		}
		Option::setMonstring( $this->pl_id, $this->path );
		$value = Option::get( $key );
		if( is_string( $value ) ) {
			return $value;
		}
		return $value;
	}
}