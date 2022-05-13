<?php
	require('I2G.php');


	echo '<div style="display:flex;">';
		echo '<img src="flower1.jpg" style="width:200px;height:200px"/>';
		$i2g = new I2G('flower1.jpg');
		$gradients = $i2g->get_gradients();
		echo '<div style="margin-left:20px;width:200px;height:200px;background:'.$gradients.';"></div>';
	echo '</div>';


	echo '<div style="margin-top:20px; display:flex;">';
		echo '<img src="flower2.jpg" style="width:200px;height:200px"/>';
		$i2g = new I2G('flower2.jpg');
		$gradients = $i2g->get_gradients();
		echo '<div style="margin-left:20px;width:200px;height:200px;background:'.$gradients.';"></div>';
	echo '</div>';


	echo '<div style="margin-top:20px; display:flex;">';
		echo '<img src="flower3.jpg" style="width:200px;height:200px"/>';
		$i2g = new I2G('flower3.jpg');
		$gradients = $i2g->get_gradients();
		echo '<div style="margin-left:20px;width:200px;height:200px;background:'.$gradients.';"></div>';
	echo '</div>';

