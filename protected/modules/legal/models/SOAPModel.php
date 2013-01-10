<?php
/**
 * User: Forgon
 * Date: 09.01.13
 */
abstract class SOAPModel extends CModel {
	// TODO model returning
	public static function model($className=__CLASS__) {
		return new $className();
	}

}