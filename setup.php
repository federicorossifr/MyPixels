<?php
	$r =  mkdir("./pics");
	if($r)
		header("Location: index.php");
	else
		header("Location: index.php?err=1");