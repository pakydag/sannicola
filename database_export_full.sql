-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: crm_baseweb
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acl_rules`
--

DROP TABLE IF EXISTS `acl_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `access` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `acl_rules_user_id_foreign` (`user_id`),
  CONSTRAINT `acl_rules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_rules`
--

LOCK TABLES `acl_rules` WRITE;
/*!40000 ALTER TABLE `acl_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ai_tickets`
--

DROP TABLE IF EXISTS `ai_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ai_tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `assistance_type` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_tickets`
--

LOCK TABLES `ai_tickets` WRITE;
/*!40000 ALTER TABLE `ai_tickets` DISABLE KEYS */;
INSERT INTO `ai_tickets` VALUES (1,'Test','Test Co','Test User','Test issue','open','2026-03-04 16:24:58','2026-03-04 16:24:58'),(2,'Applicativi Web','Bicap SRL','Pasquale Dagnello','Non si apre il mio sito internet','open','2026-03-04 16:34:31','2026-03-04 16:34:31'),(3,'Applicativi Web','Pippo Spa','Roberto Baggio','Errore durante il salvataggio di un\'immagine sul sito internet.','open','2026-03-04 16:37:57','2026-03-04 16:37:57');
/*!40000 ALTER TABLE `ai_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titolo` varchar(255) NOT NULL,
  `visibile` tinyint(1) NOT NULL DEFAULT 1,
  `has_contact_form` tinyint(1) NOT NULL DEFAULT 0,
  `ordine` int(11) NOT NULL DEFAULT 0,
  `slug` varchar(255) DEFAULT NULL,
  `sottotitolo` varchar(255) DEFAULT NULL,
  `descrizione` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `allegato` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `section_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_unique` (`slug`),
  KEY `articles_section_id_foreign` (`section_id`),
  CONSTRAINT `articles_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'prova news',1,0,0,'1-it','sottotitolo news','<p>descrizione news</p>','C:\\xampp\\tmp\\php682A.tmp','https://www.repubblica.it','articoli/allegati/4RH9PWyYPIHAul3Uoomlb2kM9Xyqhk3gJvvBfltl.pdf',NULL,NULL,NULL,'2026-02-26 16:40:43','2026-02-27 09:23:04',1),(2,'come eravano',1,1,0,'come-eravano-at','come saremo','<p>come diventeremodiventeremodiventeremodiventeremodiventeremo diventeremodiventeremodiventeremo</p>',NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-26 16:42:47','2026-02-27 16:01:20',2),(3,'rere',1,0,0,'3-it','rer','<p>rer</p>',NULL,NULL,NULL,'da silva è con noi','finalmente da silva rientra dal suo infortunio','http://localhost/baseweb/public/storage/varie/cappello.jpg','2026-02-27 09:21:03','2026-02-27 15:44:54',1),(4,'Informativa Form Contatti',1,0,0,'4-it',NULL,'<p>Ai sensi e per gli effetti del Regolamento UE 679/2016 in materia di tutela dei dati personali delle persone fisiche,&nbsp;<strong>Pasticceria Capricci</strong>&nbsp;con sede in<strong>&nbsp;Via Fratelli Cervi, 66, 76015 Trinitapoli BT Italia</strong>, in qualità di titolare del trattamento, di seguito denominata “scrivente”, informa che i dati personali acquisiti formeranno oggetto di trattamento nel rispetto della normativa sopra richiamata e La informa circa i seguenti aspetti del trattamento:<br><br>1) i dati personali verranno trattati esclusivamente per gli scopi connessi ai fini istituzionali della scrivente, ovvero dipendenti da obblighi di legge, ivi compresa l’adozione di misure di sicurezza;<br><br>2) i dati personali potranno essere trattati anche per finalità di informazione circa le attività promozionali e di formazione promosse dalla scrivente, anche con newsletter a mezzo e-mail e/o fax e/o posta di superficie. Per questo particolare trattamento, verrà richiesto in un secondo tempo un ulteriore esplicito consenso informato;<br><br>3) i dati personali verranno trattati manualmente e con strumenti automatizzati, conservati per la durata prevista e alla fine distrutti;<br><br>4) il conferimento dei dati è obbligatorio per beneficiare dei servizi richiesti e l’eventuale diniego comporta l’impossibilità per la scrivente di erogare il servizio o prodotto richiesto;<br><br>5) i dati personali non saranno diffusi presso terzi ma potranno essere comunicati a clienti e fornitori ed enti pubblici per l’espletamento di obblighi di legge qualora si instaurasse un rapporto contrattuale;<br><br>6) l’interessato gode dei diritti assicurati dall’artt.15 – &nbsp;22 del Regolamento UE 679/2016, che potranno essere esercitati mediante apposita richiesta al titolare o al responsabile del trattamento;<br><br>7) titolare del trattamento&nbsp;<strong>Pasticceria Capricci</strong>&nbsp;con sede in<strong>&nbsp;Via Fratelli Cervi, 66, 76015 Trinitapoli BT Italia,&nbsp;</strong></p><p>info@pasticceriacapricci.it</p><p><br><br>Si comunica, inoltre, che l’informativa completa è consultabile all’indirizzo:&nbsp;https://informativa-form-contatti-it&nbsp;</p>',NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-27 12:49:53','2026-02-27 12:49:53',4),(5,'Informativa Newsletter',1,0,0,'5-it',NULL,'<p><strong>INFORMATIVA SUL TRATTAMENTO DEI DATI PERSONALI</strong><br><strong>PARTNER COMMERCIALI</strong><br>Art.13 del Regolamento UE 2016/679 del 27/04/2016<br><br>Ai sensi dell’articolo 13 del Regolamento UE 2016/679 del 27/04/2016, di seguito denominato come<strong>&nbsp;</strong>(Regolamento Generale per la Protezione dei Dati Personali),&nbsp;<strong>Pasticceria Capricci</strong>, con sede legale in&nbsp;<strong>Via Fratelli Cervi, 66, 76015 Trinitapoli BT Italia</strong>, in qualità di titolare del trattamento dei dati personali, La informa in merito a quanto segue:<br><br><strong>Titolare e Responsabile del trattamento</strong><br>&nbsp;</p><p>Il titolare del trattamento è <strong>Pasticceria Capricci</strong>, con sede legale in&nbsp;<strong>Via Fratelli Cervi, 66, 76015 Trinitapoli BT Italia</strong><br><br><strong>Finalità del trattamento</strong><br>I dati personali da Lei forniti verranno trattati esclusivamente per le seguenti finalità:</p><ol><li>stipulazione ed esecuzione del contratto e di tutte le attività ad esso connesse, quali, a titolo esemplificativo, fatturazione, tutela del credito, servizi amministrativi, gestionali, organizzativi e funzionali all’esecuzione del contratto;</li><li>adempimento degli obblighi previsti dalla legge, regolamenti, normativa applicabile e altre disposizioni impartite da autorità investite dalla legge e da organi di vigilanza e controllo.</li></ol><p>Il trattamento dei dati personali per le finalità di cui sopra non richiede il Suo consenso espresso (art. 6 lett. a) e b) del RGPD).</p><ol><li>svolgimento di attività di marketing e promozionali di prodotti e servizi del Titolare, comunicazioni commerciali, sia con mezzi automatizzati senza intervento dell’operatore (es. sms, fax, mms, posta elettronica ecc.) che tradizionali (tramite telefono, posta).</li><li>iscrizione alla Newsletter.</li></ol><p>Il trattamento dei dati personali per le finalità di cui sopra richiede il suo consenso espresso (art. 7 del RGPD). Detto consenso riguarda sia le modalità di comunicazione automatizzate che quelle tradizionali sopra descritte. Lei avrà sempre il diritto di opporsi in maniera agevole e gratuitamente, in tutto o anche solo in parte al trattamento dei Suoi dati per dette finalità, escludendo ad esempio le modalità automatizzate di contatto ed esprimendo la sua volontà di ricevere comunicazioni commerciali e promozionali esclusivamente attraverso modalità tradizionali di contatto.<br><br><strong>Natura obbligatoria o facoltativa del conferimento dei dati e conseguenze di un eventuale rifiuto di fornire i dati personali</strong><br><br>I dati richiesti per le finalità di cui alle precedenti lettere a) e b) devono essere obbligatoriamente forniti per l’adempimento degli obblighi di legge e/o per la conclusione ed esecuzione del rapporto contrattuale e la fornitura dei servizi richiesti. Pertanto il Suo eventuale rifiuto, anche parziale, di fornire tali dati comporterebbe l’impossibilità per il Fornitore di instaurare e gestire il rapporto stesso e di fornire il servizio richiesto.<br><br>Il conferimento dei dati personali necessari per le finalità di cui alle precedenti lettere c) e d) è facoltativo, pertanto il Suo eventuale rifiuto di fornire tali dati comporterebbe l’impossibilità di porre in essere le attività ivi descritte.<br><br><strong>Modalità di trattamento dei dati</strong><br><br>Il trattamento dei dati personali è realizzato per mezzo delle operazioni indicate all’art. 4 n. 2) RGPD, per le finalità di cui sopra, sia su supporto cartaceo che informatico, per mezzo di strumenti elettronici o comunque automatizzati, nel rispetto della normativa vigente in particolare in materia di riservatezza e sicurezza e in conformità ai principi di correttezza, liceità e trasparenza e tutela dei diritti del Cliente.<br>Il trattamento è svolto direttamente dall’organizzazione del titolare, dai suoi responsabili e/o incaricati.<br><br><strong>Comunicazione e Diffusione</strong><br><br>I Suoi dati personali potranno essere comunicati, nei limiti strettamente pertinenti agli obblighi, ai compiti ed alle finalità di cui sopra e nel rispetto della normativa vigente in materia, alle seguenti categorie di soggetti:&nbsp;</p><ol><li>soggetti a cui tale comunicazione deve essere effettuata al fine di adempiere o per esigere l’adempimento di specifici obblighi previsti da leggi, da regolamenti e/o dalla normativa comunitaria;</li><li>società appartenenti al Gruppo del Titolare ovvero controllanti, controllate o collegate ai sensi dell’Art. 2359 Cod.Civ., che agiscono in qualità di responsabili del trattamento o per finalità amministrativo contabili (finalità connesse allo svolgimento di attività di natura organizzativa interna, amministrativa, finanziaria e contabile, in particolare, funzionali all’adempimento di obblighi contrattuali e precontrattuali);</li><li>persone fisiche e/o giuridiche esterne che forniscono servizi strumentali alle attività del Titolare per le finalità di cui al precedente punto 1. (es. call center, fornitori, consulenti, società, enti, studi professionali). Tali soggetti opereranno in qualità di responsabili del trattamento.</li></ol><p>I dati personali non saranno in alcun modo oggetto di diffusione.<br><br><strong>Periodo di conservazione dei dati personali</strong><br><br>I dati personali saranno conservati per l’intera durata espressa dal contratto stipulato con il Titolare concluso il quale i dati saranno conservati per l’espletazione dei termini previsti per legge per la conservazione dei documenti amministrativi dopodiché saranno eliminati.</p><p><strong>Trasferimento dei dati</strong><br><br>I dati personali sono conservati su server ubicati all’interno dell’Unione Europea. Resta in ogni caso inteso che il Titolare, ove si rendesse necessario, avrà facoltà di spostare i server anche extra-UE. In tal caso, il Titolare assicura sin d’ora che il trasferimento dei dati extra-UE avverrà in conformità alle disposizioni di legge applicabili, previa stipula delle clausole contrattuali standard previste dalla Commissione Europea<strong>.</strong><br><br><strong>Diritti dell’interessato</strong><br>Nella Sua qualità di interessato, ha i diritti di cui all’art.15 RGPD e precisamente i diritti di:</p><ol><li>ottenere la conferma dell’esistenza o meno di dati personali che La riguardano, anche se non ancora registrati, e la loro comunicazione in forma intelligibile;</li><li>ottenere l’indicazione: a) dell’origine dei dati personali; b) delle finalità e modalità del trattamento; c) della logica applicata in caso di trattamento effettuato con l’ausilio di strumenti elettronici; d) degli estremi identificativi del titolare, dei responsabili e del rappresentante designato ai sensi dell’art. 3, comma 1, RGPD; e) dei soggetti o delle categorie di soggetti ai quali i dati personali possono essere comunicati o che possono venirne a conoscenza in qualità di rappresentante designato nel territorio dello Stato, di responsabili o incaricati;</li><li>ottenere: a) l’aggiornamento, la rettificazione ovvero, quando vi ha interesse, l’integrazione dei dati; b) la cancellazione, la trasformazione in forma anonima o il blocco dei dati trattati in violazione di legge, compresi quelli di cui non è necessaria la conservazione in relazione agli scopi per i quali i dati sono stati raccolti o successivamente trattati; c) l’attestazione che le operazioni di cui alle lettere a) e b) sono state portate a conoscenza, anche per quanto riguarda il loro contenuto, di coloro ai quali i dati sono stati comunicati, eccettuato il caso in cui tale adempimento si rivela impossibile o comporta un impiego di mezzi manifestamente sproporzionato rispetto al diritto tutelato;</li><li>opporsi, in tutto o in parte: a) per motivi legittimi al trattamento dei dati personali che La riguardano, ancorché pertinenti allo scopo della raccolta; b) al trattamento di dati personali che La riguardano a fini di invio di materiale pubblicitario o di vendita diretta o per il compimento di ricerche di mercato o di comunicazione commerciale, mediante l’uso di sistemi automatizzati di chiamata senza l’intervento di un operatore mediante e-mail e/o mediante modalità di marketing tradizionali mediante telefono e/o posta cartacea. Si fa presente che il diritto di opposizione dell’interessato, esposto al precedente punto b), per finalità di marketing diretto mediante modalità automatizzate si estende a quelle tradizionali e che comunque resta salva la possibilità per l’interessato di esercitare il diritto di opposizione anche solo in Pertanto, l’interessato può decidere di ricevere solo comunicazioni mediante modalità tradizionali ovvero solo comunicazioni automatizzate oppure nessuna delle due tipologie di comunicazione. Ove applicabili, ha altresì i diritti di cui agli artt. 16-21 RGPD (Diritto di rettifica, diritto all’oblio, diritto di limitazione di trattamento, diritto alla portabilità dei dati, diritto di opposizione), nonché il diritto di reclamo all’Autorità Garante</li></ol><p>Per l’esercizio dei diritti di cui dall’artt. 15 – 22&nbsp; del GDPR o per domande o informazioni in ordine al trattamento dei Suoi dati ed alle misure di sicurezza adottate, potrà in ogni caso inoltrare alla nostra società la richiesta al seguente indirizzo:<br><br><strong>&nbsp;</strong></p>','http://localhost/baseweb/public/storage/1/888.jpg',NULL,NULL,NULL,NULL,NULL,'2026-02-27 12:50:42','2026-02-27 13:34:03',4),(6,'Contatti',1,1,0,'contatti','compila il form e sarai ricontattato','<p>Lasci i tuoi dati per essere ricontattato</p>',NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-27 16:16:45','2026-02-27 16:17:08',5);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_customers`
--

DROP TABLE IF EXISTS `booking_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `nazione` varchar(255) DEFAULT NULL,
  `citta` varchar(255) DEFAULT NULL,
  `attivo` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_customers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_customers`
--

LOCK TABLES `booking_customers` WRITE;
/*!40000 ALTER TABLE `booking_customers` DISABLE KEYS */;
INSERT INTO `booking_customers` VALUES (1,'pasquale','dagnello','dagnellopasquale@gmail.com','$2y$12$v8AZVAywMLYO/5tjiTz0VesB.EJz5yrSitWcinLMFMD4DXUWdMxpS','3290952802','italia','barletta',1,'ShymsadPVpLH60N8Jtxk5pgI7TnMbkDfLfP3HcwqoHvUJkYCUsUna4DzS5B2','2026-03-03 15:32:46','2026-03-03 15:32:46'),(2,'paky','dag','paky@exestudios.com','$2y$12$fHbwIcA48K3Y7l9omLvNeO/SLoLvJC5TnD/2NcHx.6h6uvAkmvMvm','08831955218','italia','bari',1,NULL,'2026-03-03 15:53:35','2026-03-03 15:53:35');
/*!40000 ALTER TABLE `booking_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_extras`
--

DROP TABLE IF EXISTS `booking_extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_extras` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `prezzo` decimal(10,2) NOT NULL,
  `ordine` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_extras`
--

LOCK TABLES `booking_extras` WRITE;
/*!40000 ALTER TABLE `booking_extras` DISABLE KEYS */;
INSERT INTO `booking_extras` VALUES (1,'Culla',10.00,1,'2026-03-04 09:52:29','2026-03-04 09:52:29'),(2,'Aria condizionata',5.00,2,'2026-03-04 09:52:40','2026-03-04 09:52:40');
/*!40000 ALTER TABLE `booking_extras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_photos`
--

DROP TABLE IF EXISTS `booking_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_structure_id` bigint(20) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_photos_booking_structure_id_foreign` (`booking_structure_id`),
  CONSTRAINT `booking_photos_booking_structure_id_foreign` FOREIGN KEY (`booking_structure_id`) REFERENCES `booking_structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_photos`
--

LOCK TABLES `booking_photos` WRITE;
/*!40000 ALTER TABLE `booking_photos` DISABLE KEYS */;
INSERT INTO `booking_photos` VALUES (1,2,'http://localhost/baseweb/public/storage/articoli/foto/NKog4vfBc2UqpQJVycmIZ75wAP4eFBraFRPDY1LM.jpg','2026-03-03 14:49:27','2026-03-03 14:49:27'),(3,1,'http://localhost/baseweb/public/storage/varie/cappello.jpg','2026-03-03 14:49:49','2026-03-03 14:49:49');
/*!40000 ALTER TABLE `booking_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_prices`
--

DROP TABLE IF EXISTS `booking_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_structure_id` bigint(20) unsigned NOT NULL,
  `booking_variant_id` bigint(20) unsigned DEFAULT NULL,
  `tipo` varchar(255) NOT NULL COMMENT 'fisso, adulto, bambino',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `prezzo` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_prices_booking_structure_id_foreign` (`booking_structure_id`),
  KEY `booking_prices_booking_variant_id_foreign` (`booking_variant_id`),
  CONSTRAINT `booking_prices_booking_structure_id_foreign` FOREIGN KEY (`booking_structure_id`) REFERENCES `booking_structures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_prices_booking_variant_id_foreign` FOREIGN KEY (`booking_variant_id`) REFERENCES `booking_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_prices`
--

LOCK TABLES `booking_prices` WRITE;
/*!40000 ALTER TABLE `booking_prices` DISABLE KEYS */;
INSERT INTO `booking_prices` VALUES (87,3,1,'persona','2026-01-01','2026-05-31',100.00,'2026-03-04 09:43:41','2026-03-04 09:43:41'),(88,3,1,'persona','2026-06-01','2026-09-30',150.00,'2026-03-04 09:43:41','2026-03-04 09:43:41'),(89,3,1,'persona','2026-10-01','2026-12-31',100.00,'2026-03-04 09:43:41','2026-03-04 09:43:41'),(90,3,2,'persona','2026-01-01','2026-12-31',80.00,'2026-03-04 09:43:41','2026-03-04 09:43:41'),(91,3,3,'persona','2026-01-01','2026-12-31',50.00,'2026-03-04 09:43:41','2026-03-04 09:43:41'),(92,4,NULL,'fisso','2026-01-01','2026-05-31',100.00,'2026-03-04 09:52:55','2026-03-04 09:52:55'),(93,4,NULL,'fisso','2026-06-01','2026-09-30',150.00,'2026-03-04 09:52:55','2026-03-04 09:52:55'),(94,4,NULL,'fisso','2026-10-01','2026-12-31',90.00,'2026-03-04 09:52:55','2026-03-04 09:52:55');
/*!40000 ALTER TABLE `booking_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_service_categories`
--

DROP TABLE IF EXISTS `booking_service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_service_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `icona` varchar(255) NOT NULL DEFAULT '✓',
  `ordine` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_service_categories`
--

LOCK TABLES `booking_service_categories` WRITE;
/*!40000 ALTER TABLE `booking_service_categories` DISABLE KEYS */;
INSERT INTO `booking_service_categories` VALUES (1,'Risotrante','🅿️',1,'2026-03-04 09:35:33','2026-03-04 09:35:33');
/*!40000 ALTER TABLE `booking_service_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_services`
--

DROP TABLE IF EXISTS `booking_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_service_category_id` bigint(20) unsigned NOT NULL,
  `nome` varchar(255) NOT NULL,
  `ordine` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_services_booking_service_category_id_foreign` (`booking_service_category_id`),
  CONSTRAINT `booking_services_booking_service_category_id_foreign` FOREIGN KEY (`booking_service_category_id`) REFERENCES `booking_service_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_services`
--

LOCK TABLES `booking_services` WRITE;
/*!40000 ALTER TABLE `booking_services` DISABLE KEYS */;
INSERT INTO `booking_services` VALUES (1,1,'cena all\'aperto',1,'2026-03-04 09:35:44','2026-03-04 09:35:44'),(2,1,'Cena in casa',2,'2026-03-04 09:35:54','2026-03-04 09:35:54');
/*!40000 ALTER TABLE `booking_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_structure_extras`
--

DROP TABLE IF EXISTS `booking_structure_extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_structure_extras` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_structure_id` bigint(20) unsigned NOT NULL,
  `booking_extra_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_structure_extras_booking_structure_id_foreign` (`booking_structure_id`),
  KEY `booking_structure_extras_booking_extra_id_foreign` (`booking_extra_id`),
  CONSTRAINT `booking_structure_extras_booking_extra_id_foreign` FOREIGN KEY (`booking_extra_id`) REFERENCES `booking_extras` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_structure_extras_booking_structure_id_foreign` FOREIGN KEY (`booking_structure_id`) REFERENCES `booking_structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_structure_extras`
--

LOCK TABLES `booking_structure_extras` WRITE;
/*!40000 ALTER TABLE `booking_structure_extras` DISABLE KEYS */;
INSERT INTO `booking_structure_extras` VALUES (1,4,1,NULL,NULL);
/*!40000 ALTER TABLE `booking_structure_extras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_structure_services`
--

DROP TABLE IF EXISTS `booking_structure_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_structure_services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_structure_id` bigint(20) unsigned NOT NULL,
  `booking_service_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `structure_service_unique` (`booking_structure_id`,`booking_service_id`),
  KEY `booking_structure_services_booking_service_id_foreign` (`booking_service_id`),
  CONSTRAINT `booking_structure_services_booking_service_id_foreign` FOREIGN KEY (`booking_service_id`) REFERENCES `booking_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_structure_services_booking_structure_id_foreign` FOREIGN KEY (`booking_structure_id`) REFERENCES `booking_structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_structure_services`
--

LOCK TABLES `booking_structure_services` WRITE;
/*!40000 ALTER TABLE `booking_structure_services` DISABLE KEYS */;
INSERT INTO `booking_structure_services` VALUES (1,4,1,NULL,NULL),(2,4,2,NULL,NULL),(3,3,1,NULL,NULL),(4,3,2,NULL,NULL);
/*!40000 ALTER TABLE `booking_structure_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_structures`
--

DROP TABLE IF EXISTS `booking_structures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_structures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descrizione` text DEFAULT NULL,
  `bagni` int(11) NOT NULL DEFAULT 1,
  `camere_letto` int(11) NOT NULL DEFAULT 1,
  `posti_totali` int(11) NOT NULL DEFAULT 1,
  `costo_al_giorno` decimal(10,2) NOT NULL,
  `tipo_prezzo` varchar(255) NOT NULL DEFAULT 'fisso',
  `attivo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_structures`
--

LOCK TABLES `booking_structures` WRITE;
/*!40000 ALTER TABLE `booking_structures` DISABLE KEYS */;
INSERT INTO `booking_structures` VALUES (1,'struttura base','<p>bellissima struttura</p>',1,1,2,10.00,'fisso',1,'2026-03-03 14:24:37','2026-03-03 14:49:49'),(2,'Struttura nuova','<p>Finalmente dispnibile la nuova struttura</p>',2,2,5,20.00,'fisso',1,'2026-03-03 14:49:27','2026-03-03 14:49:27'),(3,'struttura con fasce di età','<p>struttura new</p>',1,1,5,10.00,'persona',1,'2026-03-04 07:58:17','2026-03-04 09:43:41'),(4,'Struttura senza fasce età',NULL,1,1,5,10.00,'fisso',1,'2026-03-04 08:56:56','2026-03-04 09:52:55');
/*!40000 ALTER TABLE `booking_structures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_variants`
--

DROP TABLE IF EXISTS `booking_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_structure_id` bigint(20) unsigned NOT NULL,
  `nome` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_variants_booking_structure_id_foreign` (`booking_structure_id`),
  CONSTRAINT `booking_variants_booking_structure_id_foreign` FOREIGN KEY (`booking_structure_id`) REFERENCES `booking_structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_variants`
--

LOCK TABLES `booking_variants` WRITE;
/*!40000 ALTER TABLE `booking_variants` DISABLE KEYS */;
INSERT INTO `booking_variants` VALUES (1,3,'Adulto','2026-03-04 08:35:01','2026-03-04 08:35:01'),(2,3,'Ragazzi (9-17 anni)','2026-03-04 08:35:01','2026-03-04 08:35:01'),(3,3,'Bambini (0-8 anni)','2026-03-04 08:35:01','2026-03-04 08:35:01');
/*!40000 ALTER TABLE `booking_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `booking_structure_id` bigint(20) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `adulti` int(11) NOT NULL DEFAULT 1,
  `bambini` int(11) NOT NULL DEFAULT 0,
  `ospiti_dettaglio` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ospiti_dettaglio`)),
  `extra_dettaglio` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra_dettaglio`)),
  `totale_prezzo` decimal(10,2) NOT NULL,
  `stato` varchar(255) NOT NULL DEFAULT 'attesa',
  `stato_pagamento` varchar(255) NOT NULL DEFAULT 'attesa',
  `metodo_pagamento` varchar(255) DEFAULT NULL,
  `note_admin` text DEFAULT NULL,
  `stripe_payment_link` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_customer_id_foreign` (`customer_id`),
  KEY `bookings_booking_structure_id_foreign` (`booking_structure_id`),
  CONSTRAINT `bookings_booking_structure_id_foreign` FOREIGN KEY (`booking_structure_id`) REFERENCES `booking_structures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,NULL,1,'2026-03-30','2026-03-31',0,0,NULL,NULL,0.00,'confermato','pagato','manuale','famiglia di firenze',NULL,'2026-03-03 14:39:36','2026-03-03 14:39:36'),(2,2,1,'2026-03-07','2026-03-08',0,0,NULL,NULL,0.00,'confermato','non_pagato','manuale','Blocco manuale',NULL,'2026-03-03 15:09:50','2026-03-03 15:09:50'),(3,1,1,'2026-03-18','2026-03-19',1,0,NULL,NULL,10.00,'confermato','pagato','stripe',NULL,NULL,'2026-03-03 15:32:47','2026-03-03 15:33:44'),(4,1,1,'2026-03-09','2026-03-10',1,0,NULL,NULL,10.00,'confermato','pagato','stripe',NULL,NULL,'2026-03-03 15:49:51','2026-03-03 15:50:18'),(5,1,2,'2026-03-11','2026-03-12',2,0,NULL,NULL,20.00,'in_attesa','non_pagato','bonifico',NULL,NULL,'2026-03-03 15:51:01','2026-03-03 15:51:01'),(6,2,2,'2026-03-19','2026-03-20',0,0,NULL,NULL,0.00,'annullato','pagato','manuale','Blocco manuale',NULL,'2026-03-03 15:53:37','2026-03-03 16:05:06'),(7,1,1,'2026-03-27','2026-03-28',0,0,NULL,NULL,0.00,'confermato','non_pagato','manuale','Blocco manuale',NULL,'2026-03-03 16:06:07','2026-03-03 16:06:07'),(8,1,1,'2026-03-16','2026-03-17',0,0,NULL,NULL,0.00,'confermato','non_pagato','manuale','Blocco manuale',NULL,'2026-03-03 16:08:48','2026-03-03 16:08:48'),(9,1,1,'2026-03-20','2026-03-21',0,0,NULL,NULL,10.00,'confermato','pagato','stripe','Blocco manuale',NULL,'2026-03-03 16:16:55','2026-03-03 16:17:27'),(10,1,1,'2026-04-01','2026-04-03',0,0,NULL,NULL,20.00,'confermato','non_pagato','bonifico','Blocco manuale',NULL,'2026-03-03 16:18:07','2026-03-03 16:18:07'),(11,1,1,'2026-04-27','2026-04-30',0,0,NULL,NULL,30.00,'confermato','non_pagato','stripe','Blocco manuale',NULL,'2026-03-03 16:21:16','2026-03-03 16:21:16'),(12,1,2,'2026-04-13','2026-04-14',0,0,NULL,NULL,20.00,'confermato','non_pagato','stripe','Blocco manuale',NULL,'2026-03-03 16:25:12','2026-03-03 16:25:12'),(13,1,2,'2026-03-21','2026-03-22',0,0,NULL,NULL,20.00,'confermato','non_pagato','stripe','Blocco manuale',NULL,'2026-03-03 16:27:16','2026-03-03 16:27:16'),(14,1,1,'2026-03-04','2026-03-05',0,0,NULL,NULL,10.00,'confermato','pagato','stripe','Blocco manuale',NULL,'2026-03-03 16:29:06','2026-03-04 07:35:29'),(15,1,2,'2026-03-04','2026-03-05',0,0,NULL,NULL,20.00,'confermato','non_pagato','bonifico','Blocco manuale',NULL,'2026-03-03 16:30:33','2026-03-03 16:30:33'),(16,1,2,'2026-04-23','2026-04-29',0,0,NULL,NULL,120.00,'confermato','non_pagato','stripe','Blocco manuale','https://checkout.stripe.com/c/pay/cs_test_a1JwJfV3NShqQnp3HkTqz9VeNra7LJ0kA07Z365T8ln1WZ1MRg9g6y0Wp9#fidnandhYHdWcXxpYCc%2FJ2FgY2RwaXEnKSdkdWxOYHwnPyd1blpxYHZxWjA0TXRmTXFAXDNtY1BWU0lkVGk3N1RISH9DQGtNb1xuT3FdRjRASkxfMkdCaUBrU1VDMVwzQUZgaUYwSWREY2cyVlVOMURKPE8wbk5BfUhjbV9nN0hyUkR3NTVtUnx3V3x2RicpJ2N3amhWYHdzYHcnP3F3cGApJ2dkZm5id2pwa2FGamlqdyc%2FJyZjY2NjY2MnKSdpZHxqcHFRfHVgJz8ndmxrYmlgWmxxYGgnKSdga2RnaWBVaWRmYG1qaWFgd3YnP3F3cGB4JSUl','2026-03-04 07:48:26','2026-03-04 07:48:27'),(17,1,3,'2026-03-23','2026-03-25',1,0,'{\"1\":\"1\",\"2\":\"0\",\"3\":\"0\"}',NULL,200.00,'in_attesa','non_pagato','bonifico',NULL,NULL,'2026-03-04 09:10:40','2026-03-04 09:10:40');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_requests`
--

DROP TABLE IF EXISTS `contact_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ragione_sociale` varchar(255) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `richiesta` text NOT NULL,
  `letto` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_requests`
--

LOCK TABLES `contact_requests` WRITE;
/*!40000 ALTER TABLE `contact_requests` DISABLE KEYS */;
INSERT INTO `contact_requests` VALUES (1,'cedam srl','pasquale','dagnello','3290952802','p.dagnello@cedam.it','richiesta ok',1,'2026-02-27 16:01:53','2026-02-27 16:03:39');
/*!40000 ALTER TABLE `contact_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `ragione_sociale` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `indirizzo` varchar(255) DEFAULT NULL,
  `cap` varchar(255) DEFAULT NULL,
  `citta` varchar(255) DEFAULT NULL,
  `provincia` varchar(255) DEFAULT NULL,
  `nazione` varchar(255) NOT NULL DEFAULT 'Italia',
  `codice_fiscale` varchar(255) DEFAULT NULL,
  `partita_iva` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `cellulare` varchar(255) DEFAULT NULL,
  `sdi` varchar(255) DEFAULT NULL,
  `pec` varchar(255) DEFAULT NULL,
  `metodo_pagamento_preferito` varchar(255) DEFAULT NULL,
  `attivo` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'paky','dagnello',NULL,'admin@admin.com','$2y$12$.3QDQ0D7BJZsnuAlsnjbEug1DxzDL76vKR6AG4RxDn/YRmmbnw9a2','via ferdinando d\'aragona','76121','BARLETTA',NULL,'Italia',NULL,NULL,'3290952802',NULL,NULL,NULL,NULL,1,NULL,'2026-03-02 15:38:34','2026-03-02 15:42:29'),(2,'Pasquale','Dagnello',NULL,'dagnellopasquale@gmail.com','$2y$12$osACceIebIs8yl8td/LUVe.MKnkd2vYWwUj6lbwbysxYOE6VOJGji','via ferdinando d\'aragona','76121','barletta',NULL,'italia',NULL,NULL,'3290952802',NULL,NULL,NULL,NULL,1,NULL,'2026-03-02 15:48:22','2026-03-03 15:09:50');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_widgets`
--

DROP TABLE IF EXISTS `global_widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_widgets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titolo` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_widgets`
--

LOCK TABLES `global_widgets` WRITE;
/*!40000 ALTER TABLE `global_widgets` DISABLE KEYS */;
INSERT INTO `global_widgets` VALUES (1,'prova','video','{\"video_url\":\"http:\\/\\/localhost\\/baseweb\\/public\\/storage\\/video\\/1.mp4\"}','2026-02-27 14:54:38','2026-02-27 14:54:38'),(2,'galleri di prova','gallery','{\"photos\":[{\"url\":\"http:\\/\\/localhost\\/baseweb\\/public\\/storage\\/articoli\\/foto\\/NKog4vfBc2UqpQJVycmIZ75wAP4eFBraFRPDY1LM.jpg\",\"link\":null},{\"url\":\"http:\\/\\/localhost\\/baseweb\\/public\\/storage\\/articoli\\/foto\\/ZYIs90iGais7QLcA0oGktAHbgE4JlPDlEbXJMGC1.png\",\"link\":null},{\"url\":\"http:\\/\\/localhost\\/baseweb\\/public\\/storage\\/varie\\/cappello.jpg\",\"link\":null}]}','2026-02-27 15:08:23','2026-02-27 15:08:23'),(3,'test blocchi a specchio','mirror_blocks','{\"source_section_id\":\"1\",\"limit\":\"5\"}','2026-02-27 15:08:39','2026-02-27 15:08:39'),(4,'benvenuti','single_block','{\"subtitle\":\"finalment eil nostr nuovo sito\",\"link\":\"https:\\/\\/www.repubblica.it\",\"image\":null,\"bg_color\":\"#8f3232\"}','2026-02-27 16:24:40','2026-02-27 16:24:40'),(5,'richiama sezione','section_grid','{\"source_section_id\":\"1\",\"limit\":\"18\",\"columns\":\"3\"}','2026-02-27 16:27:59','2026-02-27 16:28:52');
/*!40000 ALTER TABLE `global_widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home_blocks`
--

DROP TABLE IF EXISTS `home_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `home_blocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `global_widget_id` bigint(20) unsigned NOT NULL,
  `ordine` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `home_blocks_global_widget_id_foreign` (`global_widget_id`),
  CONSTRAINT `home_blocks_global_widget_id_foreign` FOREIGN KEY (`global_widget_id`) REFERENCES `global_widgets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_blocks`
--

LOCK TABLES `home_blocks` WRITE;
/*!40000 ALTER TABLE `home_blocks` DISABLE KEYS */;
INSERT INTO `home_blocks` VALUES (15,4,0,'2026-02-27 16:28:11','2026-02-27 16:28:11'),(16,1,1,'2026-02-27 16:28:11','2026-02-27 16:28:11'),(17,5,2,'2026-02-27 16:28:11','2026-02-27 16:28:11'),(18,3,3,'2026-02-27 16:28:11','2026-02-27 16:28:11'),(19,2,4,'2026-02-27 16:28:11','2026-02-27 16:28:11');
/*!40000 ALTER TABLE `home_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"5989e78a-4c63-43dd-a623-641629115e0b\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:4:\\\"webp\\\";}s:7:\\\"quality\\\";a:1:{i:0;i:80;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:4:\\\"webp\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1772129410,\"delay\":null}',0,NULL,1772129410,1772129410),(2,'default','{\"uuid\":\"8c861f36-0bbf-48bc-8e2e-c37259f4e350\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:4:\\\"webp\\\";}s:7:\\\"quality\\\";a:1:{i:0;i:80;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:4:\\\"webp\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1772187763,\"delay\":null}',0,NULL,1772187763,1772187763),(3,'default','{\"uuid\":\"0ee87d81-c6fd-4ff6-8744-df3c5bb45db8\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:4:\\\"webp\\\";}s:7:\\\"quality\\\";a:1:{i:0;i:80;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:4:\\\"webp\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1772187784,\"delay\":null}',0,NULL,1772187784,1772187784),(4,'default','{\"uuid\":\"941fcf80-fa83-4b8b-ac49-cab8ec7529ce\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:4:\\\"webp\\\";}s:7:\\\"quality\\\";a:1:{i:0;i:80;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:4:\\\"webp\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1772202765,\"delay\":null}',0,NULL,1772202765,1772202765),(5,'default','{\"uuid\":\"4f453bb7-d395-4960-a2ee-60ae7b410cf9\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:2:{i:0;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:300;}s:6:\\\"height\\\";a:1:{i:0;i:300;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:3:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:4:\\\"webp\\\";}s:7:\\\"quality\\\";a:1:{i:0;i:80;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:0:{}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:4:\\\"webp\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:0:\\\"\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1772202843,\"delay\":null}',0,NULL,1772202843,1772202843);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,'App\\Models\\Article',2,'f1158fa0-0d8c-4a94-aae0-5528ac0a3077','foto','888','888.jpg','image/jpeg','public','public',152673,'[]','[]','[]','[]',1,'2026-02-26 17:10:10','2026-02-26 17:10:10'),(2,'App\\Models\\Article',3,'90d05401-a852-4e13-ae35-7237a853954c','foto','dasilva','dasilva.jpg','image/jpeg','public','public',32867,'[]','[]','[]','[]',1,'2026-02-27 09:22:43','2026-02-27 09:22:43'),(3,'App\\Models\\Article',1,'a81f7363-6fe7-47d0-bab3-0b74da7bbfaf','foto','malcore','malcore.jpg','image/jpeg','public','public',35449,'[]','[]','[]','[]',1,'2026-02-27 09:23:04','2026-02-27 09:23:04'),(5,'App\\Models\\Article',5,'d59c6981-3a56-4d2f-a37d-e36701b95f4e','foto','888','888.jpg','image/jpeg','public','public',152673,'[]','[]','[]','[]',1,'2026-02-27 13:34:03','2026-02-27 13:34:03');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_02_26_172338_create_articles_table',2),(5,'2026_02_26_172932_create_sections_table',3),(6,'2026_02_26_173453_add_section_id_to_articles_table',4),(7,'2026_02_26_180351_create_media_table',5),(8,'2026_02_27_082511_add_slug_to_articles_and_sections_tables',6),(9,'2026_02_27_102434_add_visibile_to_articles_table',7),(10,'2026_02_27_103139_add_tipo_to_sections_table',8),(11,'2026_02_27_104931_add_menu_a_tendina_to_sections_table',9),(12,'2026_02_27_111535_add_colonne_griglia_to_sections_table',10),(13,'2019_02_06_174631_make_acl_rules_table',11),(14,'2026_02_27_151059_create_widgets_table',11),(15,'2026_02_27_154603_create_global_widgets_table',12),(16,'2026_02_27_160431_make_titolo_nullable_on_widgets_table',13),(17,'2026_02_27_161138_create_home_blocks_table',14),(18,'2026_02_27_163532_add_seo_fields_to_articles_table',15),(19,'2026_02_27_163533_add_seo_fields_to_sections_table',15),(20,'2026_02_27_163534_create_settings_table',15),(21,'2026_02_27_165239_add_has_contact_form_to_articles_table',16),(22,'2026_02_27_165239_create_contact_requests_table',16),(23,'2026_02_27_175207_add_ordine_to_articles_table',17),(24,'2026_03_02_101731_create_shop_categories_table',18),(25,'2026_03_02_101732_create_shop_collections_table',18),(26,'2026_03_02_102540_create_shop_products_table',19),(27,'2026_03_02_102540_create_shop_variants_table',19),(28,'2026_03_02_102540_z_create_tags_table',20),(29,'2026_03_02_102541_create_shop_product_tags_table',21),(30,'2026_03_02_103924_create_customers_table',22),(31,'2026_03_02_103924_create_shop_orders_table',23),(32,'2026_03_02_103925_create_shop_order_items_table',24),(33,'2026_03_02_111053_create_shop_collection_tags_table',25),(34,'2026_03_02_154136_add_quantita_to_shop_products_variants_table',26),(35,'2026_03_03_113256_add_preferred_payment_to_customers_table',27),(36,'2026_03_03_144630_create_booking_structures_table',28),(37,'2026_03_03_144634_create_booking_photos_table',28),(38,'2026_03_03_144639_create_bookings_table',28),(39,'2026_03_03_153734_add_note_admin_to_bookings_table',29),(40,'2026_03_03_155058_add_payment_link_to_bookings_table',30),(41,'2026_03_03_161817_create_booking_customers_table',31),(42,'2026_03_03_173142_change_stripe_payment_link_to_text_in_bookings_table',32),(43,'2026_03_04_085508_add_pricing_type_to_booking_structures_table',33),(44,'2026_03_04_085508_create_booking_prices_table',33),(48,'2026_03_04_091604_create_booking_variants_table',34),(49,'2026_03_04_091605_update_booking_prices_with_variants',34),(50,'2026_03_04_091606_add_ospiti_dettaglio_to_bookings_table',34),(51,'2026_03_04_112500_create_booking_service_categories_table',35),(52,'2026_03_04_112501_create_booking_services_table',35),(53,'2026_03_04_112502_create_booking_structure_services_table',35),(54,'2026_03_04_104702_create_booking_extras_table',36),(55,'2026_03_04_104703_add_extra_dettaglio_to_bookings_table',36),(56,'2026_03_04_104703_create_booking_structure_extras_table',36),(57,'2026_03_04_141509_update_users_table_for_permissions',37),(58,'2026_03_04_151823_create_ai_tickets_table',38);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `contenuto` text DEFAULT NULL,
  `tipo` varchar(255) NOT NULL DEFAULT 'archivio',
  `menu_a_tendina` tinyint(1) NOT NULL DEFAULT 0,
  `colonne_griglia` tinyint(3) unsigned NOT NULL DEFAULT 3,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_image` varchar(255) DEFAULT NULL,
  `ordine` int(11) NOT NULL DEFAULT 0,
  `visibile` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sections_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'news','1-it',NULL,'archivio',1,4,'le ultime ultime news news','le ultimissime','http://localhost/baseweb/public/storage/articoli/foto/NKog4vfBc2UqpQJVycmIZ75wAP4eFBraFRPDY1LM.jpg',3,1,'2026-02-26 16:40:10','2026-02-27 16:55:55'),(2,'chi siamo','2-it','<p>rerere</p>','pagina',0,3,NULL,NULL,NULL,0,1,'2026-02-26 16:42:14','2026-02-27 16:55:55'),(3,'pèèpèp','3-it',NULL,'archivio',0,3,NULL,NULL,NULL,4,0,'2026-02-27 09:01:07','2026-02-27 16:55:55'),(4,'Informative','informative',NULL,'archivio',0,1,NULL,NULL,NULL,1,1,'2026-02-27 12:48:13','2026-03-02 07:53:26'),(5,'Contattaci','contatti','<p>Compila il form per essere ricontattato</p>','pagina',0,3,'conatattaci','contattaci',NULL,2,1,'2026-02-27 16:16:00','2026-02-27 16:55:55');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('7vOdJYpvJRxAWqFjZKcgM6yvEaSHt8A6hYrqtnRi',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidXVCTDZtWUZCcEFlbE1La1U5VHcwNVFtaDJHd0dqbU1pT3RucmhPRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly90aW5rbGluZ2x5LXVuam9sbHktY2FtcmVuLm5ncm9rLWZyZWUuZGV2L2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1772643338),('8hgmPwthmwIYwV9dYkdR66slH4CzPkz9OWfs0e2B',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoibzZad0tZdGdoT0o5MU4yMXBVbkpyOEFLenNEdU5mNFNpZ0NPY2RvbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO3M6NToicm91dGUiO3M6MTE6InB1YmxpYy5ob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1772634141),('jxHgpUmFWVKwuR9JWoY00bVN0BRKloMZz9X3VmMd',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMW5YUlZQaVk4SkVzRWlsa2xoQVJEZlhpR3F5aGFQWHJnZ2RkRmpZUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjI6Imh0dHA6Ly9sb2NhbGhvc3QvYmFzZXdlYi9wdWJsaWMvc2hvcC9jYXRlZ29yaWEvc290dG8tY2F0ZWdvcmlhIjtzOjU6InJvdXRlIjtzOjIxOiJwdWJsaWMuc2hvcC5jYXRlZ29yaWEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1772633226),('Qk4pUPzfFSPQTNMTqjfECIVNaWxXDEBPe8SuOttz',1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoic0pLSmZiN0ZVNGxzWXZMcGVkYmdSOFRZVlk5OTVPc2ZoRHhDZmlWSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo2MzoibG9naW5fYm9va2luZ19jdXN0b21lcl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly9sb2NhbGhvc3QvYmFzZXdlYi9wdWJsaWMvYW1taW5pc3RyYXppb25lL3ZhcGkiO3M6NToicm91dGUiO3M6MTY6ImFkbWluLnZhcGkuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1772646603);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'home_seo_title','benvenuti sul nostro sito','2026-02-27 15:47:28','2026-02-27 15:47:28'),(2,'home_seo_description','il nostro web site è new','2026-02-27 15:47:28','2026-02-27 15:47:28'),(3,'home_seo_image','http://localhost/baseweb/public/storage/2/dasilva.jpg','2026-02-27 15:47:28','2026-02-27 15:47:28'),(4,'site_logo','http://localhost/baseweb/public/storage/logo/cedam_mobile.jpg','2026-02-27 16:46:06','2026-02-27 16:46:06'),(5,'mail_mailer','smtp','2026-02-27 16:46:06','2026-02-27 16:46:06'),(6,'mail_host','server.exestudios.it','2026-02-27 16:46:06','2026-03-02 16:16:05'),(7,'mail_port','25','2026-02-27 16:46:06','2026-03-02 14:25:04'),(8,'mail_username','paky@exestudios.com','2026-02-27 16:46:06','2026-03-02 14:24:06'),(9,'mail_password','pakexepassdf90','2026-02-27 16:46:06','2026-03-02 14:24:06'),(10,'mail_encryption',NULL,'2026-02-27 16:46:06','2026-02-27 16:46:06'),(11,'mail_from_address','paky@exestudios.com','2026-02-27 16:46:06','2026-03-02 14:24:06'),(12,'mail_from_name','paky','2026-02-27 16:46:06','2026-03-02 14:24:06'),(13,'shop_enabled','1','2026-03-02 09:42:15','2026-03-04 13:33:10'),(14,'stripe_key','pk_test_51HqcHtEY6hfUSVLaQl22QMMzFEnHjYkJtXC1EOIZ7BGlEnVPF4Y6DCelC5LaAfb7SPK4AO9J5kKDxMfhZb2MwWAr00hWyrRysC','2026-03-03 09:52:11','2026-03-03 09:52:11'),(15,'stripe_secret','sk_test_51HqcHtEY6hfUSVLa4AebeNzTPNqXy9OctZ7wCCa783YzAJKMqj9nXttWVt9W8SNnzt5o2tizHK1bqFCePXlLXtJk00wwhjpBXM','2026-03-03 09:52:11','2026-03-03 09:52:11'),(16,'bonifico_intestazione','VIAGGI SRL','2026-03-03 10:24:13','2026-03-03 15:59:24'),(17,'bonifico_banca','BANCA DI BARLETTA','2026-03-03 10:24:13','2026-03-03 15:59:24'),(18,'bonifico_iban','IT44444488888','2026-03-03 10:24:13','2026-03-03 15:59:24'),(19,'payment_stripe_enabled','0','2026-03-03 10:24:13','2026-03-04 13:33:10'),(20,'payment_bonifico_enabled','0','2026-03-03 10:24:13','2026-03-03 10:58:14'),(21,'payment_contrassegno_enabled','0','2026-03-03 10:24:13','2026-03-03 10:58:14'),(22,'paypal_client_id','Aeu0AGzWfjT3mCSvuI4jkno6jO9bxGIT3xo43dwIKAq289LmJKI-Wd0QisBN4bjqStpiJDNlA5cxXhZZ','2026-03-03 10:35:15','2026-03-03 10:49:52'),(23,'paypal_secret','EGZUjoR4C0U8ZZ5x3hAdUE1jxN692BLubkUek5_lWMe4mLlYQnNAy7NYJ5tDzKiqEYXQYzD1iCUe81MZ','2026-03-03 10:35:15','2026-03-03 10:53:19'),(24,'paypal_mode','sandbox','2026-03-03 10:35:15','2026-03-03 10:35:15'),(25,'payment_paypal_enabled','0','2026-03-03 10:35:15','2026-03-04 13:33:10'),(26,'booking_enabled','1','2026-03-03 13:49:37','2026-03-04 13:33:10'),(27,'booking_payment_stripe_enabled','0','2026-03-03 15:32:07','2026-03-04 13:33:10'),(28,'booking_payment_paypal_enabled','0','2026-03-03 15:32:07','2026-03-04 13:33:10'),(29,'booking_payment_bonifico_enabled','0','2026-03-03 15:32:07','2026-03-04 13:33:10');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_categories`
--

DROP TABLE IF EXISTS `shop_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `visibile` tinyint(1) NOT NULL DEFAULT 1,
  `ordine` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shop_categories_slug_unique` (`slug`),
  KEY `shop_categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `shop_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `shop_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_categories`
--

LOCK TABLES `shop_categories` WRITE;
/*!40000 ALTER TABLE `shop_categories` DISABLE KEYS */;
INSERT INTO `shop_categories` VALUES (1,'Cappello','cappello',NULL,1,0,'2026-03-02 10:07:38','2026-03-02 10:07:38'),(2,'Macrocategoria','macrocategoria',NULL,1,0,'2026-03-04 10:03:34','2026-03-04 10:03:34'),(3,'categoria','categoria',2,1,0,'2026-03-04 10:03:51','2026-03-04 10:03:51'),(4,'sotto categoria','sotto-categoria',3,1,0,'2026-03-04 10:04:13','2026-03-04 10:04:13');
/*!40000 ALTER TABLE `shop_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_collection_tags`
--

DROP TABLE IF EXISTS `shop_collection_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_collection_tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_collection_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_collection_tags_shop_collection_id_foreign` (`shop_collection_id`),
  KEY `shop_collection_tags_tag_id_foreign` (`tag_id`),
  CONSTRAINT `shop_collection_tags_shop_collection_id_foreign` FOREIGN KEY (`shop_collection_id`) REFERENCES `shop_collections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shop_collection_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_collection_tags`
--

LOCK TABLES `shop_collection_tags` WRITE;
/*!40000 ALTER TABLE `shop_collection_tags` DISABLE KEYS */;
INSERT INTO `shop_collection_tags` VALUES (1,1,2,NULL,NULL),(2,1,1,NULL,NULL);
/*!40000 ALTER TABLE `shop_collection_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_collections`
--

DROP TABLE IF EXISTS `shop_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_collections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `visibile` tinyint(1) NOT NULL DEFAULT 1,
  `ordine` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shop_collections_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_collections`
--

LOCK TABLES `shop_collections` WRITE;
/*!40000 ALTER TABLE `shop_collections` DISABLE KEYS */;
INSERT INTO `shop_collections` VALUES (1,'mare','mare','http://localhost/baseweb/public/storage/articoli/foto/NKog4vfBc2UqpQJVycmIZ75wAP4eFBraFRPDY1LM.jpg',1,0,'2026-03-02 10:40:52','2026-03-02 13:06:31');
/*!40000 ALTER TABLE `shop_collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_order_items`
--

DROP TABLE IF EXISTS `shop_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_order_id` bigint(20) unsigned NOT NULL,
  `shop_variant_id` bigint(20) unsigned DEFAULT NULL,
  `nome_prodotto` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `colore` varchar(255) DEFAULT NULL,
  `taglia` varchar(255) DEFAULT NULL,
  `quantita` int(11) NOT NULL DEFAULT 1,
  `prezzo_unitario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotale` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_order_items_shop_order_id_foreign` (`shop_order_id`),
  KEY `shop_order_items_shop_variant_id_foreign` (`shop_variant_id`),
  CONSTRAINT `shop_order_items_shop_order_id_foreign` FOREIGN KEY (`shop_order_id`) REFERENCES `shop_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shop_order_items_shop_variant_id_foreign` FOREIGN KEY (`shop_variant_id`) REFERENCES `shop_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_order_items`
--

LOCK TABLES `shop_order_items` WRITE;
/*!40000 ALTER TABLE `shop_order_items` DISABLE KEYS */;
INSERT INTO `shop_order_items` VALUES (1,2,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-02 15:42:29','2026-03-02 15:42:29'),(2,3,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-02 15:48:22','2026-03-02 15:48:22'),(3,4,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-02 16:06:47','2026-03-02 16:06:47'),(4,5,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-02 16:17:26','2026-03-02 16:17:26'),(5,6,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-02 16:40:40','2026-03-02 16:40:40'),(6,7,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-03 09:52:53','2026-03-03 09:52:53'),(7,8,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-03 09:53:32','2026-03-03 09:53:32'),(8,9,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-03 09:59:32','2026-03-03 09:59:32'),(9,10,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-03 09:59:44','2026-03-03 09:59:44'),(10,11,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-03 10:00:10','2026-03-03 10:00:10'),(11,12,1,'T-shirt demo','sku_1_1','blu','S',1,10.00,10.00,'2026-03-03 10:02:15','2026-03-03 10:02:15'),(12,13,2,'T-shirt demo','sku_1_2','blu','M',1,10.00,10.00,'2026-03-03 10:05:28','2026-03-03 10:05:28'),(13,14,2,'T-shirt demo','sku_1_2','blu','M',1,10.00,10.00,'2026-03-03 10:24:49','2026-03-03 10:24:49'),(14,15,3,'T-shirt demo','sku_1_3','nero','S',1,10.00,10.00,'2026-03-03 10:25:23','2026-03-03 10:25:23'),(15,16,3,'T-shirt demo','sku_1_3','nero','S',1,10.00,10.00,'2026-03-03 10:36:24','2026-03-03 10:36:24'),(16,17,3,'T-shirt demo','sku_1_3','nero','S',1,10.00,10.00,'2026-03-03 10:37:03','2026-03-03 10:37:03'),(17,18,3,'T-shirt demo','sku_1_3','nero','S',1,10.00,10.00,'2026-03-03 10:37:26','2026-03-03 10:37:26'),(18,19,2,'T-shirt demo','sku_1_2','blu','M',1,10.00,10.00,'2026-03-03 10:43:27','2026-03-03 10:43:27'),(19,20,2,'T-shirt demo','sku_1_2','blu','M',1,10.00,10.00,'2026-03-03 10:47:43','2026-03-03 10:47:43'),(20,21,2,'T-shirt demo','sku_1_2','blu','M',2,10.00,20.00,'2026-03-03 10:50:28','2026-03-03 10:50:28'),(21,22,2,'T-shirt demo','sku_1_2','blu','M',1,10.00,10.00,'2026-03-03 10:53:44','2026-03-03 10:53:44');
/*!40000 ALTER TABLE `shop_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_orders`
--

DROP TABLE IF EXISTS `shop_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL,
  `numero_ordine` varchar(255) NOT NULL,
  `stato` varchar(255) NOT NULL DEFAULT 'nuovo',
  `stato_pagamento` varchar(255) NOT NULL DEFAULT 'attesa',
  `metodo_pagamento` varchar(255) NOT NULL DEFAULT 'bonifico',
  `note_cliente` text DEFAULT NULL,
  `note_admin` text DEFAULT NULL,
  `totale_imponibile` decimal(10,2) NOT NULL DEFAULT 0.00,
  `totale_iva` decimal(10,2) NOT NULL DEFAULT 0.00,
  `totale_ordine` decimal(10,2) NOT NULL DEFAULT 0.00,
  `spedizione_nome` varchar(255) DEFAULT NULL,
  `spedizione_indirizzo` varchar(255) DEFAULT NULL,
  `spedizione_cap` varchar(255) DEFAULT NULL,
  `spedizione_citta` varchar(255) DEFAULT NULL,
  `spedizione_provincia` varchar(255) DEFAULT NULL,
  `spedizione_nazione` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shop_orders_numero_ordine_unique` (`numero_ordine`),
  KEY `shop_orders_customer_id_foreign` (`customer_id`),
  CONSTRAINT `shop_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_orders`
--

LOCK TABLES `shop_orders` WRITE;
/*!40000 ALTER TABLE `shop_orders` DISABLE KEYS */;
INSERT INTO `shop_orders` VALUES (1,1,'ORD-69A5BD0AD4FCE','nuovo','attesa','bonifico',NULL,NULL,10.00,0.00,15.00,'Admin Test','via d\'annunzio,48','76121','barletta','bt','Italia','2026-03-02 15:38:34','2026-03-02 15:38:34'),(2,1,'ORD-69A5BDF534525','nuovo','attesa','bonifico',NULL,NULL,10.00,0.00,15.00,'paky dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-02 15:42:29','2026-03-02 15:42:29'),(3,2,'ORD-69A5BF564F6C1','spedito','attesa','bonifico',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via severini,8','76121','barletta','bt','Italia','2026-03-02 15:48:22','2026-03-02 16:04:14'),(4,2,'ORD-69A5C3A7B0E40','spedito','attesa','bonifico',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-02 16:06:47','2026-03-02 16:16:22'),(5,2,'ORD-69A5C626A3462','nuovo','attesa','bonifico',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-02 16:17:26','2026-03-02 16:17:26'),(6,2,'ORD-69A5CB98C83C3','nuovo','attesa','bonifico',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-02 16:40:40','2026-03-02 16:40:40'),(7,2,'ORD-69A6BD8529473','nuovo','attesa','bonifico',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 09:52:53','2026-03-03 09:52:53'),(8,2,'ORD-69A6BDAC177E3','nuovo','attesa','bonifico',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 09:53:32','2026-03-03 09:53:32'),(9,2,'ORD-69A6BF1441207','nuovo','attesa','stripe',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 09:59:32','2026-03-03 09:59:32'),(10,2,'ORD-69A6BF209F1B6','nuovo','attesa','stripe',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 09:59:44','2026-03-03 09:59:44'),(11,2,'ORD-69A6BF3AA8438','nuovo','attesa','stripe',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:00:10','2026-03-03 10:00:10'),(12,2,'ORD-69A6BFB79B52B','nuovo','pagato','stripe',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:02:15','2026-03-03 10:02:53'),(13,2,'ORD-69A6C078367A4','spedito','pagato','stripe',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:05:28','2026-03-03 10:06:44'),(14,2,'ORD-69A6C501526B1','nuovo','attesa','bonifico',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:24:49','2026-03-03 10:24:49'),(15,2,'ORD-69A6C5235384A','nuovo','attesa','contrassegno',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:25:23','2026-03-03 10:25:23'),(16,2,'ORD-69A6C7B839F7B','nuovo','attesa','paypal',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:36:24','2026-03-03 10:36:24'),(17,2,'ORD-69A6C7DF8F89D','nuovo','attesa','paypal',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','Contrada Barba D\'Angelo 20','76123','Andria','BT','Italia','2026-03-03 10:37:03','2026-03-03 10:37:03'),(18,2,'ORD-69A6C7F61D68D','nuovo','attesa','bonifico',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:37:26','2026-03-03 10:37:26'),(19,2,'ORD-69A6C95FA9FCD','nuovo','attesa','paypal',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:43:27','2026-03-03 10:43:27'),(20,2,'ORD-69A6CA5F68015','nuovo','attesa','paypal',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:47:43','2026-03-03 10:47:43'),(21,2,'ORD-69A6CB04B39A7','nuovo','attesa','paypal',NULL,NULL,20.00,0.00,25.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:50:28','2026-03-03 10:50:28'),(22,2,'ORD-69A6CBC8680B4','annullato','attesa','paypal',NULL,NULL,10.00,0.00,15.00,'Pasquale Dagnello','via ferdinando d\'aragona','76121','BARLETTA','BARLETTA - ANDRIA - TRANI (BT)','Italia','2026-03-03 10:53:44','2026-03-03 10:56:39');
/*!40000 ALTER TABLE `shop_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_product_tags`
--

DROP TABLE IF EXISTS `shop_product_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_product_tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_product_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_product_tags_shop_product_id_foreign` (`shop_product_id`),
  KEY `shop_product_tags_tag_id_foreign` (`tag_id`),
  CONSTRAINT `shop_product_tags_shop_product_id_foreign` FOREIGN KEY (`shop_product_id`) REFERENCES `shop_products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shop_product_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_product_tags`
--

LOCK TABLES `shop_product_tags` WRITE;
/*!40000 ALTER TABLE `shop_product_tags` DISABLE KEYS */;
INSERT INTO `shop_product_tags` VALUES (1,1,1,NULL,NULL),(2,1,2,NULL,NULL);
/*!40000 ALTER TABLE `shop_product_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_products`
--

DROP TABLE IF EXISTS `shop_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `shop_category_id` bigint(20) unsigned DEFAULT NULL,
  `shop_collection_id` bigint(20) unsigned DEFAULT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `descrizione` text DEFAULT NULL,
  `foto_aggiuntive` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`foto_aggiuntive`)),
  `sku_padre` varchar(255) DEFAULT NULL,
  `visibile` tinyint(1) NOT NULL DEFAULT 1,
  `ordine` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shop_products_slug_unique` (`slug`),
  KEY `shop_products_shop_category_id_foreign` (`shop_category_id`),
  KEY `shop_products_shop_collection_id_foreign` (`shop_collection_id`),
  CONSTRAINT `shop_products_shop_category_id_foreign` FOREIGN KEY (`shop_category_id`) REFERENCES `shop_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `shop_products_shop_collection_id_foreign` FOREIGN KEY (`shop_collection_id`) REFERENCES `shop_collections` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_products`
--

LOCK TABLES `shop_products` WRITE;
/*!40000 ALTER TABLE `shop_products` DISABLE KEYS */;
INSERT INTO `shop_products` VALUES (1,'T-shirt demo','edsds',4,1,'adidas','<p>maglia nuova</p>','[\"http:\\/\\/localhost\\/baseweb\\/public\\/storage\\/articoli\\/foto\\/NKog4vfBc2UqpQJVycmIZ75wAP4eFBraFRPDY1LM.jpg\",\"http:\\/\\/localhost\\/baseweb\\/public\\/storage\\/varie\\/cappello.jpg\"]','sku_1',1,0,'2026-03-02 10:02:48','2026-03-04 10:09:42');
/*!40000 ALTER TABLE `shop_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_variants`
--

DROP TABLE IF EXISTS `shop_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_product_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `ean` varchar(255) DEFAULT NULL,
  `colore` varchar(255) DEFAULT NULL,
  `taglia` varchar(255) DEFAULT NULL,
  `prezzo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `prezzo_scontato` decimal(10,2) DEFAULT NULL,
  `quantita` int(11) NOT NULL DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shop_variants_shop_product_id_foreign` (`shop_product_id`),
  CONSTRAINT `shop_variants_shop_product_id_foreign` FOREIGN KEY (`shop_product_id`) REFERENCES `shop_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_variants`
--

LOCK TABLES `shop_variants` WRITE;
/*!40000 ALTER TABLE `shop_variants` DISABLE KEYS */;
INSERT INTO `shop_variants` VALUES (1,1,'sku_1_1','123456789123456','blu','S',10.00,NULL,0,'http://localhost/baseweb/public/storage/articoli/foto/NKog4vfBc2UqpQJVycmIZ75wAP4eFBraFRPDY1LM.jpg','2026-03-02 10:02:48','2026-03-03 10:00:10'),(2,1,'sku_1_2','123456789123457','blu','M',10.00,NULL,4,'http://localhost/baseweb/public/storage/articoli/foto/NKog4vfBc2UqpQJVycmIZ75wAP4eFBraFRPDY1LM.jpg','2026-03-02 10:03:54','2026-03-03 10:56:39'),(3,1,'sku_1_3','123456789123458','nero','S',10.00,NULL,6,'http://localhost/baseweb/public/storage/articoli/foto/ZYIs90iGais7QLcA0oGktAHbgE4JlPDlEbXJMGC1.png','2026-03-02 10:04:19','2026-03-03 10:37:26');
/*!40000 ALTER TABLE `shop_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'primavera-estate','primavera-estate','2026-03-02 10:29:25','2026-03-02 10:29:25'),(2,'mare','mare','2026-03-02 10:40:18','2026-03-02 10:40:18');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_site` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_shop` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_booking` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Test','admin','admin@admin.com','2026-02-26 15:23:48','$2y$12$u/HjPfWB6AoxygApo8WZV.ZNQEzgaUhQuPRIpSM0WsiY6KOdU7iOS','admin',1,1,1,1,'Tue5VkjENMWnZ3o8azOxTUBv5LmFgFv01ex6KYavHcgfzZIdgbUTWt1wfxyb','2026-02-26 15:23:48','2026-03-04 13:29:48'),(2,'Pasquale Dagnello',NULL,'dagnellopasquale@gmail.com',NULL,'$2y$12$ezYYCHnn.TFIzajHUSSUjuxxBWe7lEy7JnqjsthrbHu0jxdHK5XMS','user',0,0,0,0,NULL,'2026-03-02 15:48:22','2026-03-02 15:48:22'),(4,'Paky','dag','p.dagnello@cedam.it',NULL,'$2y$12$QTcff7z0rgrKD8E8jPAcheeS4oTXzJcFXnChQDe147KodNyZchkm6','admin',1,1,1,1,NULL,'2026-03-04 13:25:26','2026-03-04 13:45:34');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widgets`
--

DROP TABLE IF EXISTS `widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widgets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` bigint(20) unsigned NOT NULL,
  `titolo` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) NOT NULL,
  `ordine` int(11) NOT NULL DEFAULT 0,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `widgets_article_id_foreign` (`article_id`),
  CONSTRAINT `widgets_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widgets`
--

LOCK TABLES `widgets` WRITE;
/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;
INSERT INTO `widgets` VALUES (2,5,'test','gallery',1,'{\"photos\":[{\"url\":\"http:\\/\\/localhost\\/baseweb\\/public\\/storage\\/articoli\\/foto\\/NKog4vfBc2UqpQJVycmIZ75wAP4eFBraFRPDY1LM.jpg\",\"link\":null},{\"url\":\"http:\\/\\/localhost\\/baseweb\\/public\\/storage\\/articoli\\/foto\\/ZYIs90iGais7QLcA0oGktAHbgE4JlPDlEbXJMGC1.png\",\"link\":null},{\"url\":\"http:\\/\\/localhost\\/baseweb\\/public\\/storage\\/varie\\/cappello.jpg\",\"link\":null}]}','2026-02-27 14:22:06','2026-02-27 14:22:06'),(3,5,'news','mirror_blocks',2,'{\"source_section_id\":\"1\",\"limit\":\"10\"}','2026-02-27 14:22:50','2026-02-27 14:22:50'),(4,5,'test','video',3,'{\"video_url\":\"http:\\/\\/localhost\\/baseweb\\/public\\/storage\\/video\\/1.mp4\"}','2026-02-27 14:40:43','2026-02-27 14:40:43'),(5,3,NULL,'global_widget',1,'{\"global_widget_id\":\"1\"}','2026-02-27 15:05:20','2026-02-27 15:05:20');
/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-04 18:55:34
