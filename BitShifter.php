<?php

class BitShifter{
	private $v;
	public $map;
	private $reverseMap;

	public function __construct($map){
		$this->resetValue();
		$this->setMap($map);

		return $this;
	}

	public function __toString(){
		return (string)$this->v;
	}

    /**
     * Add a key to the mapping at an index.
     * @param $key
     * @param $i
     */
	public function __set($key, $i){
		$this->map[$key]=$i;
	}

    /**
     * Get the bit value of a key in the map.
     * @param $key
     * @return BitShifter|int
     */
	public function __get($key){
		return $this->shiftValue(0,$key);
	}

    /**
     * Set the map of keys for this bitShifter
     *
     * @param $map
     * @return $this|null
     */
	public function setMap($map){
		$this->map=array();
		$i=0;
		foreach(array_values($map) as $v){
			if(is_array($v)){
				// throw an error
				return null;
			}
			$this->map[$v]=$i;
			$this->reverseMap[$i]=$v;
			$i++;
		}
		return $this;
	}


	public function getMap(){
		return $this->map;
	}

    /**
     * Get the current bit value of this BitShifter
     * @return mixed
     */
	public function getValue(){
		return $this->v;
	}

    /**
     * Reset the bit value to 0.
     * @return $this
     */
	public function resetValue(){
		$this->v = 0;
		return $this;
	}

    /**
     * Overwrite the value to a set one.  No validations are done to make sure the new value makes sense.
     * @param $v
     * @return $this
     */
	public function setValue($v){
		$this->v = $v;
		return $this;
	}

    /**
     * Verify that a key exists within the map.
     *
     * @param $key
     * @return bool
     */
	public function checkKey($key){
		return array_key_exists($key, $this->map);
	}

    /**
     * Get the bit position value of a key from the map, null if not set.
     * @param $key
     * @return null|int
     */
	public function getKey($key){
		if($this->checkKey($key)){
			return $this->map[$key];
		}
		return null;
	}

    /**
     * Shift the value of this bitShifter by the number of positions that the key in the map points to.
     * @param $key
     * @return $this
     */
	public function shift($key){
		if(!$this->checkKey($key)){
			// throw exception
			return $this;
		}
		$this->v += 1 << $this->map[$key];
		return $this;
	}

    /**
     * Calls shift for each key in the input array.
     * @param $keyArray
     * @return $this
     */
	public function shiftByArray($keyArray){
		foreach($keyArray as $i=>$k){
			if(!$this->checkKey($k))
				continue;
			$this->shift($k);
		}
		return $this;
	}

    /**
     * Shift a given value by the amount the key points to...
     *      ie. Do a shift as though the value of the BitShifter was the input $v.
     * @param $v
     * @param $key
     * @return null|int
     */
	public function shiftValue($v, $key){
		if(!$this->checkKey($key)){
			// throw exception
			return null;
		}
		$v += 1 << $this->map[$key];
		return $v;
	}

    /**
     * Shift a given value by all the keys in the input array.
     * Calls shiftValue for the input $v for each key in the key array, accumulating the shifts, and returns it
     * @param $v
     * @param $keyArray
     * @return int
     */
	public function shiftValueByArray($v, $keyArray){

		foreach($keyArray as $i=>$k){
			if(!$this->checkKey($k))
				continue;
			$v = $this->shiftValue($v, $k);
		}
		return $v;
	}

    /**
     * Given a bit value return all the entries within the map that compose the bit value.
     *  Ie. Given '3' return the first and second keys of the map.
     * @param $v
     * @return array
     */
	public function convertValue($v){
		$t=$v;
		$map = $this->getMap();
		$entries=array();
		for($i=count($map); $i>-1; $i--){
			$r = $t>>$i;
			if($r == 1){
				$entries[]=$this->reverseMap[$i];
				$t-= 1 << $i;
			}
		}
		return $entries;
	}

    /**
     * Calls convertValue for the value stored within this BitShifter.
     * @return array
     */
	public function convert(){
		return $this->convertValue($this->getValue());
	}

    /**
     * Add another bitShifters value to this bitShifter.
     * @param $bs
     * @return $this
     */
	public function add($bs){
		$cA = $this->convert();
		$cB = $this->convertValue($bs->getValue());
		$this->resetValue();
		$x=array_unique(array_merge($cA,$cB));

		$this->shiftByArray( $x );

		return $this;
	}

    /**
     * Removes another bitShifters value from this bitShifter.
     * @param $bs
     * @return $this
     */
	public function sub($bs){
		$cA = $this->convert();
		$cB = $this->convertValue($bs->getValue());
		$this->resetValue();

		$x = array_diff($cA, $cB);
		$this->shiftByArray($x);

		return $this;
	}
}
