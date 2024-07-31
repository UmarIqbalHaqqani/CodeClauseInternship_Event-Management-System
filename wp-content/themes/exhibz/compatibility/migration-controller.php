<?php

namespace Exhibz\Compatibility;

class Migration_Controller {

	private $migrations = [
		//theme_version_date => full qualified class name
		'020300_19042024' => '\Exhibx\Compatibility\Mig_Unyson_To_Codestar',
	];

	public function init() {

		foreach ( $this->migrations as $migrate_key => $class ) {

			$op_key = 'exhibz_migration_' . $migrate_key;

			$is_done = get_option( $op_key, [] );

			if ( empty( $is_done ) ) {

				( new $class() )->init();
				$this->mark_migration_as_done( $op_key );
			}
		}
	}

	private function mark_migration_as_done( $op_key ) {

		return update_option( $op_key, [
			'time' => time(),
			'user' => - 1,
		] );
	}

}