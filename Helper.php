<?php
class Helper {
    public static function clone_array($arr) {
        $clone = array();
        foreach($arr as $k => $v) {
            if(is_array($v)) $clone[$k] = Helper::clone_array($v); //If a subarray
            else if(is_object($v)) $clone[$k] = clone $v; //If an object
            else $clone[$k] = $v; //Other primitive types.
        }
        return $clone;
    }

	// ruby: #even? ;)
	public static function isEven($int) {
		return $int % 2 == 0;
	}
}
