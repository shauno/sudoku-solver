<?php

class sudoku {
	private $originalPuzzle;
	private $currentlySolved = array();
	private $guessed = array();

	function __construct() {
	}

	public function setPuzzle($puzzle) {
		//TODO, set validate legit puzzle (0-9 rows/cols, 1-9 given values, etc)
		$this->originalPuzzle = $puzzle;
		$this->currentlySolved = $puzzle;
		return $this->originalPuzzle;
	}

	public function bruteForce() {
		static $tries = 0;
		$tries++;
		$startAgain = false;
		for($row=0; $row<9; $row++) {
			for($col=0; $col<9; $col++) {
				if(empty($this->currentlySolved[$row][$col])) {
					for($i=1; $i<10; $i++) {
						if($this->checkAllowed($row, $col, $i)) {
							$this->guessed[] = array($row, $col, $i);
							$this->currentlySolved[$row][$col] = $i;
							$startAgain = true;
							break 3;
						}
					}
					//nothing fit, time to backtrack
					$this->backTrack();
				}
			}
		}
		if($startAgain) {
			$this->bruteForce();
		}
	}

	private function checkAllowed($row, $col, $num) {
		if($this->rowHasNum($row, $num)) {
			return false;
		}

		if($this->colHasNum($col, $num)) {
			return false;
		}

		//check if num anywhere in this 3x3 block
		$block = $this->get3x3Block($row, $col);
		for($rowTest=($block[0]*3); $rowTest<($block[0]*3)+3; $rowTest++) {
			for($colTest=($block[1]*3); $colTest<($block[1]*3)+3; $colTest++) {
				if($this->currentlySolved[$rowTest][$colTest] == $num) {
					return false;
				}
			}
		}

		return true;
	}

	private function rowHasNum($row, $num) {
		for($colTest=0; $colTest<9; $colTest++) {
			if($this->currentlySolved[$row][$colTest] == $num) {
				return true;
			}
		}

		return false;
	}

	private function colHasNum($col, $num) {
		for($rowTest=0; $rowTest<9; $rowTest++) {
			if($this->currentlySolved[$rowTest][$col] == $num) {
				return true;
			}
		}

		return false;
	}

	private function get3x3Block($row, $col) {
		$rowStart = floor($row / 3);
		$colStart = floor($col / 3);

		return array($rowStart, $colStart);
	}

	private function backTrack() {
		//find last guess
		$lastGuess = array_pop($this->guessed);

		//clear it
		$this->currentlySolved[$lastGuess[0]][$lastGuess[1]] = '';

		//increment until fits
		for($i=$lastGuess[2]+1; $i<10; $i++) {
			if($this->checkAllowed($lastGuess[0], $lastGuess[1], $i)) {
				$this->guessed[] = array($lastGuess[0], $lastGuess[1], $i);
				$this->currentlySolved[$lastGuess[0]][$lastGuess[1]] = $i;
				return true;
			}
		}

		//nothing fit! go back again
		$this->backTrack();
	}

	public function show() {
		echo '<pre>';
		for($row=0; $row<9; $row++) {
				if($row == 3 || $row == 6) {
					echo '---------------------'."\n";
				}

			for($col=0; $col<9; $col++) {
				if($col == 3 || $col == 6) {
					echo '| ';
				}
				echo $this->currentlySolved[$row][$col].' ';
			}
			echo "\n";
		}
		echo '</pre>';
	}
}