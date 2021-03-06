<?php
/*
 * photograph.php
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 20 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
$HEADER_TITLE = "Question/réponses aux photographes";
$HEADER_DESCRIPTION = "Photographe ? vous trouverez ici les réponses aux questions que vous pouvez vous poser";
include("header.php");

$PERCENT = 100 - PHOTOGRAPH_INITIAL_PERCENT;
?>
<div id="full_content_top">
		Vous êtes photographe, Bienvenue parmi nous !
</div>
<div id="full_content_mid">
	<div class="path">
		<a href="index.php">Accueil</a> &gt; 
		Vous êtes photographe !
	</div>
	<div class="separator10"></div>
	<div id="photographc">
		<center><h2>Vous êtes photographe ? Ces questions peuvent vous interesser !</h2></center><br/>
		 
		<a href="#1"><div class="question">Je suis photographe, si je passe par votre service, qu'est ce que cela va me coûter ?</div></a>
		<a href="#2"><div class="question">Ok, je ne peux rien perdre, mais je pourrais gagner plus ?</div></a>
		<a href="#3"><div class="question">Pourquoi devrais-je perdre une commission au lieu de mettre ma solution en place ?</div></a>
		<a href="#4"><div class="question">Vous parlez de stockage sécurisé, c'est à dire ?</div></a>
		<a href="#5"><div class="question">Moi je privilégie la qualité, je ne veux pas me retrouver au milieu de photographes débutants logés à la même enseigne.</div></a>
		<a href="#6"><div class="question">Je viens de recevoir mon nouveau téléphone qui dispose d'un capteur énorme, puis-je m'inscrire ?</div></a>
		<a href="#8"><div class="question">Bon d'accord tout ceci semble tentant, mais qui vais-je photographier ?</div></a>
		<a href="#9"><div class="question">Oui d'accord je suis emballé, je signe où ?</div></a>
		<a href="#11"><div class="question">Et d'un point de vue général, comment je procède ?</div></a>
		<a href="#10"><div class="question">Mais au fait, comment puis-je vous transmettre mes photos ?</div></a>
		<div class="separator5"></div>
		<center><hr/></center>
		<div class="question"><a name="1"></a>Je suis photographe, si je passe par votre service, qu'est ce que cela va me coûter ?</div> 
		<div class="answer">
			<span class="start"></span>Absolument rien! Vous serez rémunérés à hauteur de <?php echo (100-$PERCENT); ?>% de vos ventes. Ce pourcentage pourra augmenter au fur et à mesure de votre fidélité et de vos prestations, les <?php echo $PERCENT; ?>% restants étant destinés à rémunérer ce service.
		</div>
		<div class="question"><a name="2"></a>Ok, je ne peux rien perdre, mais je pourrais gagner plus ?</div>
		<div class="answer">
			<span class="start"></span>Réellement ? Avez-vous considéré les coûts de mise à disposition des photographies, le temps nécessaire et le risque ? Et oui le risque puisque dans un tel cas vous êtes obligé d'investir du temps et de l'argent.<br/>
			En participant à l'aventure <b>Photomentiel</b>, vous ne prenez aucun risque, vous bénéficiez d'ailleurs de notre renommée, de notre sérieux et aussi de notre système de notation qui permet aux clients de vous faire confiance parce que nous vous faisons confiance !
		</div>
		<div class="question"><a name="3"></a>Pourquoi devrais-je perdre une commission au lieu de mettre ma solution en place ?</div>
		<div class="answer">
			<span class="start"></span>Vous vous sentez d'écrire un site internet dynamique, complet, à la pointe des innovations en terme de technologie internet, qui suit les codes et évolutions du monde internet ? Vous êtes capable financièrement et techniquement d'assurer un stockage sûr et redondant de vos photographies ? Savez vous que la durée de vie moyenne d'un disque dur fortement sollicité ne dépasse pas les trois ans ?<br/>
			<span class="start"></span>Parce que nous ne savons pas composer et capturer la réalité de façon professionnelle nous avons besoin de vous, si vous voulez vous concentrer sur la partie noble du travail photographique vous avez besoin de nous. 
		</div>
		<div class="question"><a name="4"></a>Vous parlez de stockage sécurisé, c'est à dire ?</div>
		<div class="answer">
			<span class="start"></span>Étant informaticien de formation, nous sommes à même de mettre en place l'ensemble des solutions de vérification, redondance, sauvegardes, etc ... pour assurer la conservation de vos données. L'ensemble de nos disques durs sont monitorés en temps réel et lorsque des symptômes de pannes se font ressentir, ils sont immédiatement remplacés.<br/>
			Si une panne devait survenir ? Aucun problème, chaque parcelle de données est répliquée sur plusieurs disques durs, ce qui nous permet, à chaud sans interruption de service, de remplacer le disque dur défectueux, sans aucune pertes de données. Et si cela ne vous rassure toujours pas, sachez que nous effectuons quotidiennement une copie de sauvegarde de l'ensemble des données, cette copie est ensuite entreposée dans un endroit distant du système de stockage principal et ne disposant pas d'accés internet. Nous sommes donc capable de faire face à tous types de pannes.
		</div>
		<div class="question"><a name="5"></a>Moi je privilégie la qualité, je ne veux pas me retrouver au milieu de photographes débutants logés à la même enseigne.</div>
		<div class="answer">
			<span class="start"></span>C'est notre vision première, la qualité ! C'est pour cela que notre système d'évaluation des photographes à été mis en place.
			Il permettra à nos clients (nos clients communs donc) d'avoir une garantie de qualité. Idéalemment nous souhaitons avoir un
			contact direct avec le photographe après son inscription dans le but, premièremment de donner une réponse à toutes les questions
			qui restent en suspens mais aussi de vérifier le sérieux du photographe. 
		</div>
		<div class="question"><a name="6"></a>Je viens de recevoir mon nouveau téléphone qui dispose d'un capteur énorme, puis-je m'inscrire ?</div>
		<div class="answer">
			<span class="start"></span>Non. Définitivement non. Notre but étant la qualité, vous devez avoir de solides connaissances en photographie ainsi que le matériel 
			nécessaire à fournir des clichés de qualité. 
		</div>
		<div class="question"><a name="8"></a>Bon d'accord tout ceci semble tentant, mais qui vais-je photographier ?</div>
		<div class="answer">
			<span class="start"></span>Nous ciblons principalement l'événementiel, les sorties des associations, les réunions de comités d'entreprises, tous ces événements qui sont riches en souvenir mais qui ne sont que rarement couverts par des photographes professionnels. Si vous n'avez pas le temps de parcourir les offices du tourismes, les calendriers d'associations des mairies, etc ... et bien ce n'est pas un problème, nous l'avons fait pour vous ! Rendez vous dans la rubrique <a href="events.php">événements</a> et faites votre choix ! Vous n'aurez plus qu'à contacter un responsable d'événement pour proposer vos services. N'hésitez pas aussi à ajouter les événements sur lesquels souhaitez aller s'il n'existe pas. Cela permet une meilleure visibilité de l'événement et des visiteurs pourront s'inscrire pour recevoir un mail dès que l'album correspondant sera en ligne.<br/>
			<b>Notre but est de vous permettre de vous concentrer sur votre passion : la photo.</b>
		</div>
		<div class="question"><a name="9"></a>Oui d'accord je suis emballé, je signe où ?</div>
		<div class="answer">
			<span class="start"></span>Et bien tout d'abord la loi française sur le travail ne nous permet pas de rémunérer un particulier, si vous êtes un professionnel que faites vous encore là ? La rubrique <a href="adduser.php?type=ph">inscription</a> du site vous attend !<br/>
			<span class="start"></span>Si vous êtes photographe amateur avec un réel talent, le statut d'auto-entrepreneur semble être la solution idéale, vous pouvez vous inscrire directement <a target="_blank" href="https://www.cfe.urssaf.fr/autoentrepreneur/CFE_Bienvenue">par internet en moins de 20 minutes</a> et vous recevrez votre numéro SIRET par la poste sous 15 jours. En terme de charges sociales vous ne serez imposé que sur vos bénéfices, n'hésitez pas à nous contacter pour de plus amples renseignements, nous pourrons vous conseiller au mieux. 
		</div>
		<div class="question"><a name="11"></a>Et d'un point de vue général, comment je procède ?</div>
		<div class="answer">
			<span class="start"></span>C'est très simple ! Vous commencez par créer un compte. Vous pourrez ensuite accéder à votre espace dans lequel il vous sera possible de créer des albums.<br/>
			Pour créer un album, appuyez sur le bouton prévu à cet effet et laissez vous guider.<br/>
			Une fois l'album créé, vous pourrez imprimer directement vos carte de visites, qui contiennent les directives nécessaires pour accéder à cet album.<br/>
			Vous serez aussi en mesure de créer une mailing liste qui nous permettra d'envoyer un Email à toutes les peronnes souhaitées pour les avertir de la publication de votre album.<br/>
			Une fois vos photos sur nos serveurs, l'équipe photomentiel procèdera à l'activation de celui-ci s'il respecte les conditions de publication. 
		</div>
		<div class="question"><a name="10"></a>Mais au fait, comment puis-je vous transmettre mes photos ?</div>
		<div class="answer">
			<span class="start"></span>Pour cela plusieurs solutions, tout d'abord, vous ne souhaitez pas vous déplacer et l'ensemble des données que vous souhaitez transmettre n'est pas trop volumineux, alors vous pouvez transmettre vos photos directement au travers d'internet, les explications sur le transfert des photos vous seront communiquées à la création d'un album. Le temps de transfert dépendra de votre connexion internet.<br/>
			<!--<span class="start"></span>Si le temps de transfert est un problème, vous pouvez aussi nous transmettre vos photographies sur n'importe quel support numérique (carte mémoire, CD-ROM, DVD-ROM, clé usb, etc.) par les services postaux à l'adresse :-->
			<span class="start"></span>Si le temps de transfert est un problème, vous pouvez aussi nous contacter pour nous remettre les photos en mains propres si vous êtes en région PACA !<br/>
			<span class="start"></span>Vous pourrez aussi <u>bientôt</u> nous faire parvenir vos photographies sur n'importe quel support numérique (carte mémoire, CD-ROM, DVD-ROM, clé usb, etc.) par les services postaux.
			<br/>
			<br/>
			<a href="adduser.php?type=ph">Allez, je vais m'inscrire !</a>
			<br/>
			<br/>
		</div>
	</div>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
?>
