<?php
  if (isset($_SESSION['start']))
	print('
		<footer>
		  	<div class="footer">
		  		<a href="news.php">'.FOOTER_R3.'</a>
		  		<span>|</span>
		  		<a href="edit_profil.php">'.FOOTER_R1.'</a>
		  		<span>|</span>
		  		<a onclick="javascript:logout();">'.FOOTER_R2.'</a>
		  		<span>|</span>
		  		<p>© Copyright ybitton</p>
		  	</div>
		</footer>
	');
print('</body></html>');
?>