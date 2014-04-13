<?php

require 'sudoku.php';

if(!empty($_POST['puzzle'])) {
	$puzzle = new sudoku();
	if($puzzle->setPuzzle($_POST['puzzle'])) {
		$puzzle->bruteForce();
		$puzzle->show();
	}
}else{
	?>
	<form method="post" action="">
		<table>
			<?php 
			for($row=0; $row<9; $row++) {
				if($row == 3 || $row == 6) {
					echo '<tr><td colspan="11"><hr /></td></tr>';
				}
				echo '<tr>';
				for($col=0; $col<9; $col++) {
					if($col == 3 || $col == 6) {
						echo '<td>|</td>';
					}
					echo '<td>';
					echo '<input type="text" name="puzzle['.$row.']['.$col.']" size="2" maxlength=1 />';
					echo '</td>';
				}
				echo '</tr>';
			}
			?>
		</table>

		<input type="submit" value="Solve" />
	</form>
	<?php
}