

		<h2>Routes</h2>
		<table border="1">
			<tr><td><b>Id</td></b><td><b>Row</b></td><td><b>Times</b></td></tr>
				<?php while($row = $routes->_fetch_assoc()){?>
					<tr>
						<td><b><?php echo ($row['id']);?></b></td>
						<td><?php echo ($row['route']); ?></td>
                        <td><?php echo $row['time'];  ?></td>
					</tr>
				<?php } ;?>
				</table>
