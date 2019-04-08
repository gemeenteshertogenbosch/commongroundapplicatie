<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{ 
	/**
	* @Route("/")
	*/
	public function indexAction()
	{
		// Lets make a list of team members
		$team = [];
		$team[] = ['id'=> 1,'icon'=> null,'name'=>'Ruben van der Linde','function'=>'Lead Developer','description'=>'De omdenker en bouwer.','image'=>'images/avatar_ruben.png'];
		$team[] = ['id'=> 2,'icon'=> null,'name'=>'Marleen Romijn','function'=>'Social en Training','description'=>'Ons social brein, zowel on- als offline','image'=>'images/avatar_marleen.png'];
		$team[] = ['id'=> 3,'icon'=> null,'name'=>'Matthias Oliveiro','function'=>'Design en Testing','description'=>'Houdt de kwaliteit hoog.','image'=>'images/avatar_matthias.png'];
		
		// Lets make a list of applications
		$applications = [];		
		
		// Lets make a list of components
		$components=[];
		$components[] = [
				'id'=> 1,
				'icon'=>'fa-lg fas fa-calendar-check',
				'name'=>'Agenda',
				'summary'=>'Afspraken en Beschikbaarheid',
				'description'=>'Agendas van objecten uit overige componenten met daaraan gekoppelde afspraken en beschikbaarheid',
				'images'=> [
						'images/large-images/large_agenda.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://agendas.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/agendas-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/agendas'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/agendas/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 2,
				'icon'=>'fa-lg fas fa-user-tie',
				'name'=>'Ambtenaren',
				'summary'=>'Overheidsmedewerkers',
				'description'=>'Een overzicht van medewerkers van een (semi)overheidsinstelling',
				'images'=> [
						'images/large-images/large_agenda.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://ambtenaren.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/ambtenaren-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/ambtenaren'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/ambtenaren/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 4,
				'icon'=>'fa-lg fas fa-file-invoice-dollar',
				'name'=>'Betalen',
				'summary'=>'Component voor betalen',
				'description'=>'Met dit component kun je betalingen regelen, via bijv. mollie of Ideal',
				'images'=> [
						'images/large-images/large_betalen.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://betalen.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/betalen-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/betalen'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/betalen/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 5,
				'icon'=>'fa-lg fas fa-users',
				'name'=>'BRP', 
				'summary'=>'Component voor de Basis Registratie Personen',
				'description'=>'een component waarmee we het BRP simuleren, met rest api en mogelijkheden voor het genereren van testdata voor andere componenten ',
				'images'=> [
						'images/large-images/large_brp.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://brp.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/brp-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/mock-basisregistratie-personen'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/mock-basisregistratie-personen/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 6,
				'icon'=>'fa-lg fas fa-mail-bulk',
				'name'=>'Contact Registraties',
				'summary'=>'Berichtenverkeer',
				'description'=>'Het versturen van SMS, E-Mail, Post of Whatsapp berichten naar burgers aan de hand van vooringestelde sjablonen, en het inzien van dit verkeer',
				'images'=> [
						'images/large-images/large_agenda.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://contactregistraties.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/contactregistraties-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/contactregistraties'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/contactregistraties/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 7,
				'icon'=>'fa-lg fas fa-person-booth',
				'name'=>'Instemmingen',
				'summary'=>'Verwerken instemmingen',
				'description'=>'Het via tokens of digid vaststellen (ondertekenen) van intenties , verzoeken of documenten',
				'images'=> [
						'images/large-images/large_instemeningen.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://instemmingen.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/instemmingen-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/instemmingen'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/instemmingen/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 8,
				'icon'=>'fa-lg fas fa-building',
				'name'=>'Locaties',
				'summary'=>'Overzicht van ruimtes',
				'description'=>'Een overzicht van te boeken ruimtes, hun kenmerken, beschikbaarheid en agendas',
				'images'=> [
						'images/large-images/large_agenda.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://locaties.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/locaties-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/locaties'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/locaties/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 9,
				'icon'=>'fas fa-file-invoice',
				'name'=>'Orders',
				'summary'=>'Order verwerking',
				'description'=>'Fullfilment component voor het verwerken van bestellingen, sterk gelieerd aan de betalen en producten en diensten componenten',
				'images'=> [
						'images/large-images/large_agenda.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://orders.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/orders-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/orders'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/orders/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 10,
				'icon'=>'fa-lg fas fa-box',
				'name'=>'Producten en Diensten',
				'summary'=>'Producten en diensten catalogus',
				'description'=>'Een producten en diensten catalogus',
				'images'=> [
						'images/large-images/large_agenda.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://producten-diensten.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/producten-diensten-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/producten-diensten'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/producten-diensten/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 11,
				'icon'=>'fa-lg fas fa-images',
				'name'=>'Resources',
				'summary'=>'Afbeeldingen, films en documenten',
				'description'=>'Dit component verwerkt multimedia voor bijvoorbeeld plaatsingen op websites.',
				'images'=> [
						'images/large-images/large_resources.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://resources.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/resources-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/resources'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/resources/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 12,
				'icon'=>'fa-lg fas fa-user-friends',
				'name'=>'Trouwen',
				'summary'=>'Doorgeven huwelijk/partnerschap',
				'description'=>'Dit component handelt de basis functionaliteiten rond het vormgeven van een huwelijk af, inclusief de keuze van beschikbare ambtenaar, locatie, soort huwelijk en de melding en aanvraag van het huwelijk',
				'images'=> [						
						'images/large-images/large_resources.gif'
				],
				'links'=> [
						['name'=>'online demo','url'=>'http://trouwen.demo.zaakonline.nl/'],
						['name'=>'docker container','url'=>'https://hub.docker.com/r/huwelijksplanner/trouwen-component'],
						['name'=>'codebase (git)','url'=>'https://github.com/GemeenteUtrecht/trouwen'],
						['name'=>'codebase (zip)','url'=>'https://github.com/GemeenteUtrecht/trouwen/archive/master.zip']
				]
		];
		$components[] = [
				'id'=> 13,
				'icon'=>'fa-lg fas fa-people-carry',
				'name'=>'Verhuizen',
				'summary'=>'Doorgeven verhuizing',
				'description'=>'To be continued : In ontwikkeling..',
				'images'=> [
						'images/large-images/large_agenda.gif'
				],
				'links'=> [
				]
		];
		
		// Lets make a list of libraries
		$libraries=[];
		
		return $this->render('home/index.html.twig', [
				'team' => $team,
				'applications' => $applications,
				'components' => $components,
				'libraries' => $libraries,
		]);
	}
}
