<?php
/*
 * footer.php is the footer of each page
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 24 juil. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
?>
	</div>
  	<div class="separator10"></div>
  	<div id="footer">
  		&#169; 2009 <?php echo AUTHOR; ?> - Tous droits réservés - Vie privée et Internet
  	</div>
  	<div id="end">
   		<a href="index.php">Accueil</a> | <a href="adduser.php"><?php echo isset($_SESSION['userID'])?'Modifier mon':'Créer un'; ?> compte</a> | <a href="cgu.php">Conditions Générales d'Utilisation</a> | <a href="mentions.php">Mentions Légales</a> | <a href="contact.php">Contact</a>
   		<br />&nbsp;
    </div>
 </body>
</html>
