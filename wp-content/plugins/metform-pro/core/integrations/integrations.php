<?php


namespace MetForm_Pro\Core\Integrations;


use MetForm_Pro\Core\Integrations\Email\Automizy\Automizy_Integration;

class Integrations {

	public  function init(){

		// automizy
		( new Automizy_Integration())->init();

	}

}