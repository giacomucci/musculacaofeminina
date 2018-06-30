<?php 
/**
 * AV5 Tecnologia
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL).
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Shipping (Frete)
 * @package    Av5_Correios
 * @copyright  Copyright (c) 2013 Av5 Tecnologia (http://www.av5.com.br)
 * @author     AV5 Tecnologia <anderson@av5.com.br>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Av5_Correios_Model_Mysql4_Carrier_Correios
 *
 * @category   Shipping
 * @package    Av5_Correios
 * @author     AV5 Tecnologia <anderson@av5.com.br>
 */
class Av5_Correios_Model_Mysql4_Carrier_Correios extends Mage_Core_Model_Mysql4_Abstract {
    
	/**
	 * Valores padr�o para popular a tabela de pre�os
	 * Vetor de vetores com as seguintes posi��es:
	 * 0: Regi�o atendida
	 * 1: CEP destino inicial
	 * 2: CEP destino final
	 * 3: CEP destino refer�ncia
	 */
	protected $_defaultData = array(
		array('SP - Capital',1,9999999,4811210),
		array('SP - Interior',10000000,19999999,14801000),
		array('RJ - Capital',20000000,24799999,20270270),
		array('RJ - Interior',24800001,28999999,24800001),
		array('ES - Capital',29000000,29184999,29060370),
		array('ES - Interior',29185000,29999999,29200250),
		array('MG - Capital',30000000,34999999,30190000),
		array('MG - Interior',35000000,39999999,35930075),
		array('BA - Capital',40000000,43849999,40110010),
		array('BA - Interior',43850000,48999999,44260000),
		array('SE - Capital',49000000,49099999,49027000),
		array('SE - Interior',49100000,49999999,49220000),
		array('PE - Capital',50000000,54999999,50610360),
		array('PE - Interior',55000000,56999999,55805000),
		array('AL - Capital',57000000,57099999,57046270),
		array('AL - Interior',57100000,57999999,57230000),
		array('PB - Capital',58000000,58099999,58011040),
		array('PB - Interior',58100000,58999999,58428720),
		array('RN - Capital',59000000,59099000,59030380),
		array('RN - Interior',59100000,59999999,59140840),
		array('CE - Capital',60000000,61699999,60165082),
		array('CE - Interior',61700000,63999999,61930000),
		array('PI - Capital',64000000,64099999,64001280),
		array('PI - Interior',64100000,64999999,64310000),
		array('MA - Capital',65000000,65099000,65026260),
		array('MA - Interior',65100000,65999999,65275000),
		array('PA - Capital',66000000,67999999,66010902),
		array('PA - Interior',68000000,68899999,68385000),
		array('AP - Capital',68900000,68929999,68901100),
		array('AP - Interior',68930000,68999999,68970000),
		array('AM - Capital',69000000,69099000,69020210),
		array('AM - Interior',69100000,69299000,69110000),
		array('RR - Capital',69300000,69339999,69312450),
		array('RR - Interior',69340000,69399999,69340000),
		array('AM - Interior',69400000,69899999,69470000),
		array('AC - Capital',69900000,69920999,69906380),
		array('AC - Interior',69921000,69999999,69921000),
		array('DF - Capital',70000000,73699999,70040902),
		array('GO - Interior',73700000,76799999,75044450),
		array('RO - Capital',76800000,76834999,76829684),
		array('RO - Interior',76835000,76999999,76870762),
		array('TO - Capital',77000000,77299999,77020116),
		array('TO - Interior',77300000,77999999,77818550),
		array('MT - Capital',78000000,78169999,78020010),
		array('MT - Interior',78170000,78899999,78307000),
		array('MS - Capital',79000000,79124999,79002400),
		array('MS - Interior',79125000,79999999,79200000),
		array('PR - Capital',80000000,83729999,80010000),
		array('PR - Interior',83730000,87999999,84015070),
		array('SC - Capital',88000000,88139999,88010500),
		array('SC - Interior',88140000,89999999,88220000),
		array('RS - Capital',90000000,94999999,90450090),
		array('RS - Interior',95000000,99999999,95680000)
	);
	
	protected $_81019data = array(
		array('São Paulo',1000000,9999999,4811210),
		array('Santos',11000001,11249999,11000001),
		array('Bertioga',11250000,11299999,11250001),
		array('Vinhedo',13280001,13280999,13280001),
		array('Piracicaba',13400001,13427999,13400001),
		array('Artemis ( Distrito de Piracicaba)',13432001,13432999,13432001),
		array('Nova Odessa',13460001,13464999,13460001),
		array('Ribeirão Preto',14000001,14114999,14000001),
		array('Altinópolis',14350001,14389999,14350001),
		array('Presidente Venceslau',19400000,19409999,19400000),
		array('Vila Velha',29100001,29134999,29100001),
		array('Linhares',29900001,29919999,29900001),
		array('Guarapari',29200000,29229999,29200000),
		array('Ibirité',32400000,32499999,32400000),
		array('Betim',32500001,32899999,32500001),
		array('Vespasiano',33200000,33299999,33200000),
		array('Lagoa Santa',33400000,33499999,33400000),
		array('Pedro Leopoldo',33600000,33699999,33600000),
		array('Nova Lima',34000000,34299999,34000000),
		array('Caeté',34800000,34989999,34800000),
		array('São Luis',65000000,65099999,65000000),
		array('Brasília',70000001,70639999,70000001),
		array('Cruzeiro',70640001,70699999,70640001),
		array('Brasília',70700001,70999999,70700001),
		array('Guará',71000000,71499998,71000000),
		array('Lago Norte',71500000,71569999,71500000),
		array('Lago Sul',71600000,71689999,71700000),
		array('Núcleo Bandeirante',71700000,71799999,71700000),
		array('Taguatinga',72000000,72199999,72000000),
		array('Aparecida de Goiânia',74800001,74999999,74800001),
		array('Garopaba',88495000,88495000,88495000),
		array('Santo Cristo',98960001,98969999,98960001),
		array('Sapiranga',93800001,93879999,93800001),
		array('Rio Branco',69900001,69923999,69900001),
		array('Arapiraca',57300001,57316999,57300001),
		array('Maceió',57000001,57099999,57000001),
		array('Palmeira dos Índios',57600001,57609999,57600001),
		array('Itacoatiara',69100001,69109999,69100001),
		array('Manacapuru',69400001,69409999,69400001),
		array('Manaus',69000001,69099999,69000001),
		array('Parintins',69150001,69157999,69150001),
		array('Tefé',69550001,69558999,69550001),
		array('Macapá',68900001,68911999,68900001),
		array('Santana',68925001,68939999,68925001),
		array('Alagoinhas',48000001,48099999,48000001),
		array('Barreiras',47800001,47819999,47800001),
		array('Camaçari',42800001,42819999,42800001),
		array('Candeias',43800001,43839999,43800001),
		array('Eunápolis',45820001,45828999,45820001),
		array('Feira de Santana',44000001,44099999,44000001),
		array('Ilhéus',45650001,45662999,45650001),
		array('Itabuna',45600001,45614999,45600001),
		array('Jequié',45200001,45208999,45200001),
		array('Juazeiro',48900001,48909999,48900001),
		array('Paulo Afonso',48600001,48619999,48600001),
		array('Salvador',40000001,42599999,40000001),
		array('Santo Antônio de Jesus',44570001,44574999,44570001),
		array('Teixeira de Freitas',45985001,45996999,45985001),
		array('Vitória da Conquista',45000001,45099999,45000001),
		array('Caucaia',61600001,61676999,61600001),
		array('Crato',63100001,63135999,63100001),
		array('Fortaleza',60000001,61599999,60000001),
		array('Iguatu',63500001,63514999,63500001),
		array('Juazeiro do Norte',63000001,63079999,63000001),
		array('Maracanaú',61900001,61939999,61900001),
		array('Maranguape',61940001,61949999,61940001),
		array('Quixadá',63900001,63909999,63900001),
		array('Sobral',62000001,62099999,62000001),
		array('Aracruz',29190001,29199999,29190001),
		array('Cachoeiro de Itapemirim',29300001,29320999,29300001),
		array('Cariacica',29140001,29159999,29140001),
		array('Colatina',29700001,29719999,29700001),
		array('São Mateus',29930001,29949999,29930001),
		array('Serra',29160001,29184999,29160001),
		array('Viana',29130001,29139999,29130001),
		array('Vitória',29000001,29099999,29000001),
		array('Águas Lindas de Goiás',72910001,72929999,72910001),
		array('Anápolis',75000001,75149999,75000001),
		array('Catalão',75700001,75713999,75700001),
		array('Cidade Ocidental',72880001,72898999,72880001),
		array('Formosa',73800001,73816999,73800001),
		array('Goiânia',74000001,74899999,74000001),
		array('Itumbiara',75500001,75544999,75500001),
		array('Jataí',75800001,75809999,75800001),
		array('Luziânia',72800001,72859999,72800001),
		array('Novo Gama',72860001,72869999,72860001),
		array('Planaltina',73750001,73757999,73750001),
		array('Rio Verde',75900001,75913999,75900001),
		array('Valparaíso de Goiás',72870001,72879999,72870001),
		array('Caxias',65600001,65609999,65600001),
		array('Imperatriz',65900001,65919999,65900001),
		array('São Luís',65000001,65109999,65000001),
		array('Timon',65630001,65638999,65630001),
		array('Araguari',38440001,38449999,38440001),
		array('Araxá',38180001,38184999,38180001),
		array('Barbacena',36200001,36205999,36200001),
		array('Belo Horizonte',30000001,31999999,30000001),
		array('Caratinga',35300001,35309999,35300001),
		array('Cataguases',36770001,36775999,36770001),
		array('Contagem',32000001,32399999,32000001),
		array('Coronel Fabriciano',35170001,35175999,35170001),
		array('Divinópolis',35500001,35504999,35500001),
		array('Governador Valadares',35000001,35099999,35000001),
		array('Ipatinga',35160001,35164999,35160001),
		array('Itabira',35900001,35904999,35900001),
		array('Itajubá',37500001,37506999,37500001),
		array('Itaúna',35680001,35684999,35680001),
		array('Ituiutaba',38300001,38309999,38300001),
		array('João Monlevade',35930001,35934999,35930001),
		array('Juiz de Fora',36000001,36099999,36000001),
		array('Montes Claros',39400001,39409999,39400001),
		array('Pará de Minas',35660001,35664999,35660001),
		array('Passos',37900001,37904999,37900001),
		array('Patos de Minas',38700001,38709999,38700001),
		array('Poços de Caldas',37700001,37719999,37700001),
		array('Ponte Nova',35430001,35434999,35430001),
		array('Ribeirão das Neves',33800001,33979999,33800001),
		array('Sabará',34500001,34739999,34500001),
		array('Santa Luzia',33000001,33199999,33000001),
		array('São João Del Rei',36300001,36314999,36300001),
		array('Sete Lagoas',35700001,35704999,35700001),
		array('Teófilo Otoni',39800001,39805999,39800001),
		array('Timóteo',35180001,35184999,35180001),
		array('Uberaba',38000001,38099999,38000001),
		array('Uberlândia',38400001,38415999,38400001),
		array('Varginha',37000001,37109999,37000001),
		array('Campo Grande',79000001,79124999,79000001),
		array('Corumbá',79300001,79349999,79300001),
		array('Dourados',79800001,79849999,79800001),
		array('Ponta Porã',79900001,79907999,79900001),
		array('Três Lagoas',79600001,79649999,79600001),
		array('Cuiabá',78000001,78099999,78000001),
		array('Rondonópolis',78700001,78750999,78700001),
		array('Sinop',78550001,78559999,78550001),
		array('Várzea Grande',78110001,78164999,78110001),
		array('Altamira',68370001,68377999,68370001),
		array('Ananindeua',67000001,67199999,67000001),
		array('Belém',66000001,66999999,66000001),
		array('Capanema',68700001,68704999,68700001),
		array('Castanhal',68740001,68746999,68740001),
		array('Itaituba',68180001,68187999,68180001),
		array('Marabá',68500001,68513999,68500001),
		array('Paragominas',68625001,68630999,68625001),
		array('Redenção',68550001,68554999,68550001),
		array('Santarém',68000001,68099999,68000001),
		array('Tucuruí',68455001,68460999,68455001),
		array('Xinguara',68555001,68558999,68555001),
		array('Bayeux',58305001,58309999,58305001),
		array('Cabedelo',58100001,58109999,58100001),
		array('Campina Grande',58400001,58439999,58400001),
		array('João Pessoa',58000001,58099999,58000001),
		array('Patos',58700001,58708999,58700001),
		array('Santa Rita',58300001,58303999,58300001),
		array('Sousa',58800001,58809999,58800001),
		array('Abreu e Lima',53500001,53589999,53500001),
		array('Arcoverde',56500001,56519499,56500001),
		array('Belo Jardim',55150001,55159999,55150001),
		array('Cabo de Santo Agostinho',54500001,54589999,54500001),
		array('Camaragibe',54750001,54799999,54750001),
		array('Carpina',55810001,55819800,55810001),
		array('Caruaru',55000001,55099999,55000001),
		array('Garanhuns',55290001,55299999,55290001),
		array('Gravatá',55640001,55645999,55640001),
		array('Igarassu',53600001,53659999,53600001),
		array('Jaboatão dos Guararapes',54000001,54499999,54000001),
		array('Olinda',53000001,53399999,53000001),
		array('Paulista',53400001,53499999,53400001),
		array('Petrolina',56300001,56334999,56300001),
		array('Recife',50000001,52999999,50000001),
		array('São Lourenço da Mata',54700001,54748999,54700001),
		array('Serra Talhada',56900001,56915999,56900001),
		array('Vitória de Santo Antão',55600001,55617999,55600001),
		array('Parnaíba',64200001,64219999,64200001),
		array('Picos',64600001,64608999,64600001),
		array('Teresina',64000001,64099999,64000001),
		array('Almirante Tamandaré',83500001,83529999,83500001),
		array('Apucarana',86800001,86819999,86800001),
		array('Arapongas',86700001,86714999,86700001),
		array('Araucária',83700001,83724999,83700001),
		array('Cambé',86180001,86195999,86180001),
		array('Campo Largo',83600001,83640999,83600001),
		array('Campo Mourão',87300001,87314999,87300001),
		array('Cascavel',85800001,85820999,85800001),
		array('Castro',84160001,84184999,84160001),
		array('Cianorte',87200001,87212999,87200001),
		array('Colombo',83400001,83415999,83400001),
		array('Curitiba',80000001,82999999,80000001),
		array('Fazenda Rio Grande',83820001,83835999,83820001),
		array('Foz do Iguaçu',85850001,85871999,85850001),
		array('Francisco Beltrão',85600001,85606999,85600001),
		array('Guarapuava',85000001,85099999,85000001),
		array('Laranjeiras do Sul',85300001,85319999,85300001),
		array('Londrina',86000001,86099999,86000001),
		array('Maringá',87000001,87109999,87000001),
		array('Paranaguá',83200001,83249999,83200001),
		array('Paranavaí',87700001,87721999,87700001),
		array('Pato Branco',85500001,85513999,85500001),
		array('Pinhais',83320001,83349999,83320001),
		array('Piraquara',83300001,83318999,83300001),
		array('Ponta Grossa',84000001,84099999,84000001),
		array('Rolândia',86600001,86609999,86600001),
		array('São José dos Pinhais',83000001,83149999,83000001),
		array('Sarandi',87110001,87119999,87110001),
		array('Telêmaco Borba',84260001,84274999,84260001),
		array('Toledo',85900001,85919999,85900001),
		array('Umuarama',87500001,87515999,87500001),
		array('Angra dos Reis',23900001,23959999,23900001),
		array('Barra do Piraí',27000001,27150999,27000001),
		array('Barra Mansa',27300001,27399999,27300001),
		array('Belford Roxo',26100001,26199999,26100001),
		array('Cabo Frio',28900001,28924999,28900001),
		array('Campos dos Goytacazes',28000001,28099999,28000001),
		array('Duque de Caxias',25000001,25499999,25000001),
		array('Guapimirim',25940001,25949999,25940001),
		array('Itaboraí',24800001,24889999,24800001),
		array('Itaguaí',23800001,23859999,23800001),
		array('Japeri',26400001,26499999,26400001),
		array('Macaé',27900001,27979999,27900001),
		array('Magé',25900001,25939999,25900001),
		array('Maricá',24900001,24999999,24900001),
		array('Mesquita',26550001,26599999,26550001),
		array('Nilópolis',26500001,26549999,26500001),
		array('Niterói',24000001,24399999,24000001),
		array('Nova Friburgo',28600001,28636999,28600001),
		array('Nova Iguaçu',26000001,26099999,26000001),
		array('Petrópolis',25600001,25779999,25600001),
		array('Quatis',27400001,27459999,27400001),
		array('Queimados',26300001,26399999,26300001),
		array('Resende',27500001,27549999,27500001),
		array('Rio das Ostras',28890001,28899999,28890001),
		array('Rio de Janeiro',20000001,23799999,20000001),
		array('São Gonçalo',24400001,24799999,24400001),
		array('São João de Meriti',25500001,25599999,25500001),
		array('Seropédica',23890001,23899999,23890001),
		array('Teresópolis',25950001,25995999,25950001),
		array('Três Rios',25800001,25829999,25800001),
		array('Volta Redonda',27200001,27299999,27200001),
		array('Mossoró',59600001,59649999,59600001),
		array('Natal',59000001,59139999,59000001),
		array('Parnamirim',59140001,59161999,59140001),
		array('Ariquemes',76870001,76878999,76870001),
		array('Cacoal',76960001,76968999,76960001),
		array('Ji-Paraná',76900001,76914999,76900001),
		array('Porto Velho',76800001,76834999,76800001),
		array('Boa Vista',69300001,69339999,69300001),
		array('Alegrete',97540001,97551999,97540001),
		array('Alvorada',94800001,94889999,94800001),
		array('Bagé',96400001,96429999,96400001),
		array('Cachoeira do Sul',96500001,96510999,96500001),
		array('Cachoeirinha',94900001,94999999,94900001),
		array('Canoas',92000001,92449999,92000001),
		array('Caxias do Sul',95000001,95124999,95000001),
		array('Cruz Alta',98000001,98117999,98000001),
		array('Esteio',93250001,93299999,93250001),
		array('Gravataí',94000001,94329999,94000001),
		array('Novo Hamburgo',93300001,93599999,93300001),
		array('Passo Fundo',99000001,99099999,99000001),
		array('Pelotas',96000001,96099999,96000001),
		array('Porto Alegre',90000001,91999999,90000001),
		array('Rio Grande',96200001,96219999,96200001),
		array('Santa Cruz do Sul',96800001,96849999,96800001),
		array('Santa Maria',97000001,97119999,97000001),
		array('Santana do Livramento',97570001,97584999,97570001),
		array('Santo Ângelo',98800001,98824999,98800001),
		array('São Leopoldo',93000001,93179999,93000001),
		array('Sapucaia do Sul',93200001,93249999,93200001),
		array('Uruguaiana',97500001,97514999,97500001),
		array('Viamão',94400001,94729999,94400001),
		array('Araranguá',88900001,88913999,88900001),
		array('Balneário Camboriú',88330001,88339999,88330001),
		array('Blumenau',89000001,89099999,89000001),
		array('Brusque',88350001,88359999,88350001),
		array('Camboriú',88340001,88349999,88340001),
		array('Chapecó',89800001,89816199,89800001),
		array('Criciúma',88800001,88819999,88800001),
		array('Florianópolis',88000001,88099999,88000001),
		array('Itajaí',88300001,88319999,88300001),
		array('Jaraguá do Sul',89250001,89269999,89250001),
		array('Joinville',89200001,89239999,89200001),
		array('Lages',88500001,88531999,88500001),
		array('Navegantes',88370001,88379999,88370001),
		array('Palhoça',88130001,88139999,88130001),
		array('Rio do Sul',89160001,89169999,89160001),
		array('São Bento do Sul',89280001,89293999,89280001),
		array('São José',88100001,88123999,88100001),
		array('Tubarão',88700001,88709999,88700001),
		array('Aracaju',49000001,49098999,49000001),
		array('Americana',13465001,13479999,13465001),
		array('Amparo',13900001,13909999,13900001),
		array('Andradina',16900001,16914999,16900001),
		array('Araçatuba',16000001,16129999,16000001),
		array('Araraquara',14800001,14811999,14800001),
		array('Araras',13600001,13609999,13600001),
		array('Arujá',7400001,7499999,7400001),
		array('Assis',19800001,19819999,19800001),
		array('Atibaia',12940001,12954999,12940001),
		array('Avaré',18700001,18709999,18700001),
		array('Barretos',14780001,14789999,14780001),
		array('Barueri',6400001,6499999,6400001),
		array('Bauru',17000001,17109999,17000001),
		array('Bebedouro',14700001,14718999,14700001),
		array('Birigüi',16200001,16209999,16200001),
		array('Botucatu',18600001,18619999,18600001),
		array('Bragança Paulista',12900001,12929999,12900001),
		array('Caçapava',12280001,12299999,12280001),
		array('Caieiras',7700001,7749999,7700001),
		array('Cajamar',7750001,7799999,7750001),
		array('Campinas',13000001,13139999,13000001),
		array('Campo Limpo Paulista',13230001,13239999,13230001),
		array('Capão Bonito',18300001,18308999,18300001),
		array('Caraguatatuba',11660001,11679999,11660001),
		array('Carapicuíba',6300001,6399999,6300001),
		array('Catanduva',15800001,15819999,15800001),
		array('Cotia',6700001,6729999,6700001),
		array('Cruzeiro',12700001,12759999,12700001),
		array('Cubatão',11500001,11599999,11500001),
		array('Diadema',9900001,9999999,9900001),
		array('Embu das Artes',6800001,6849999,6800001),
		array('Ferraz de Vasconcelos',8500001,8549999,8500001),
		array('Franca',14400001,14414999,14400001),
		array('Francisco Morato',7900001,7999999,7900001),
		array('Franco da Rocha',7800001,7899999,7800001),
		array('Guaratinguetá',12500001,12524999,12500001),
		array('Guarujá',11400001,11499999,11400001),
		array('Guarulhos',7000001,7399999,7000001),
		array('Hortolândia',13183001,13189999,13183001),
		array('Indaiatuba',13330001,13349999,13330001),
		array('Itapecerica da Serra',6850001,6889999,6850001),
		array('Itapetininga',18200001,18215999,18200001),
		array('Itapeva',18400001,18419999,18400001),
		array('Itapevi',6650001,6699999,6650001),
		array('Itapira',13970001,13985999,13970001),
		array('Itaquaquecetuba',8570001,8599999,8570001),
		array('Itatiba',13250001,13259999,13250001),
		array('Itu',13300001,13314999,13300001),
		array('Jaboticabal',14870001,14898999,14870001),
		array('Jacareí',12300001,12349999,12300001),
		array('Jales',15700001,15709999,15700001),
		array('Jandira',6600001,6649999,6600001),
		array('Jaú',17200001,17220999,17200001),
		array('Jundiaí',13200001,13219999,13200001),
		array('Leme',13610001,13624999,13610001),
		array('Lençóis Paulista',18680001,18687999,18680001),
		array('Limeira',13480001,13489999,13480001),
		array('Lins',16400001,16419999,16400001),
		array('Lorena',12600001,12614999,12600001),
		array('Marília',17500001,17539999,17500001),
		array('Matão',15990001,15999999,15990001),
		array('Mauá',9300001,9399999,9300001),
		array('Mococa',13730001,13749999,13730001),
		array('Mogi das Cruzes',8700001,8889999,8700001),
		array('Mogi Guaçu',13840001,13855999,13840001),
		array('Mogi Mirim',13800001,13817999,13800001),
		array('Osasco',6000001,6299999,6000001),
		array('Ourinhos',19900001,19919999,19900001),
		array('Paulínia',13140001,13149999,13140001),
		array('Pindamonhangaba',12400001,12449999,12400001),
		array('Pirassununga',13630001,13644999,13630001),
		array('Poá',8550001,8569999,8550001),
		array('Praia Grande',11700001,11729999,11700001),
		array('Presidente Prudente',19000001,19140999,19000001),
		array('Ribeirão Pires',9400001,9449999,9400001),
		array('Rio Claro',13500001,13507999,13500001),
		array('Salto',13320001,13329999,13320001),
		array('Santa Bárbara DOeste',13450001,13459999,13450001),
		array('Santana de Parnaíba',6500001,6549999,6500001),
		array('Santo André',9000001,9299999,9000001),
		array('São Bernardo do Campo',9600001,9899999,9600001),
		array('São Caetano do Sul',9500001,9599999,9500001),
		array('São Carlos',13560001,13577999,13560001),
		array('São João da Boa Vista',13870001,13879999,13870001),
		array('São José do Rio Preto',15000001,15104999,15000001),
		array('São José dos Campos',12200001,12248999,12200001),
		array('São Roque',18130001,18146999,18130001),
		array('São Vicente',11300001,11399999,11300001),
		array('Sertãozinho',14160001,14179999,14160001),
		array('Sorocaba',18000001,18109999,18000001),
		array('Sumaré',13170001,13182999,13170001),
		array('Suzano',8600001,8699999,8600001),
		array('Taboão da Serra',6750001,6799999,6750001),
		array('Tatuí',18270001,18282999,18270001),
		array('Taubaté',12000001,12119999,12000001),
		array('Tupã',17600001,17626999,17600001),
		array('Valinhos',13270001,13279999,13270001),
		array('Várzea Paulista',13220001,13229999,13220001),
		array('Votorantim',18110001,18119999,18110001),
		array('Votuporanga',15500001,15515999,15500001),
		array('Araguaína',77800001,77834999,77800001),
		array('Gurupi',77400001,77448999,77400001),
		array('Palmas',77000001,77249999,77000001),
		
	);
	
	/**
	 * Construtor da classe
	 * @see Mage_Core_Model_Resource_Abstract::_construct()
	 */
	protected function _construct(){
        $this->_init('av5_correios/correios', 'id');
    }
	
    /**
     * Recupera os pre�os de frete baseado no request do usu�rio
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return multitype:unknown
     */
    public function getRates(Mage_Shipping_Model_Rate_Request $request) {
        $read = $this->_getReadAdapter();
        $write = $this->_getWriteAdapter();

		$postcode = Mage::helper('av5_correios')->_formatZip($request->getDestPostcode());
        $table = Mage::getSingleton('core/resource')->getTableName('av5_correios/correios');
		
        $pkgWeight = ceil($request->getFixedPackageWeight());
        $postingMethods = $request->getPostingMethods();
        if ($request->getFixedPackageWeight() < 0.3) {
        	$pacCodes = $request->getPacCodes();
        	$hasPac = array_intersect($postingMethods, $pacCodes);
        	$pacString = "";
        	if ($hasPac) {
        		$pacString = "(servico in (" . implode(',',$hasPac) . ") AND peso = '" . $pkgWeight . "') OR ";
        		$postingMethods = array_diff($postingMethods, $hasPac);
        	}
        	
        	$servicesString = "(" . $pacString . "(servico in (" . implode(",", $postingMethods) . ") AND peso = '0.300') )";
        } else {
        	$servicesString = "(servico in (" . implode(",", $postingMethods) . ") AND peso = '" . $pkgWeight . "')";
        }
        
		$searchString = " AND (cep_destino_ini <= '" . $postcode . "' AND cep_destino_fim >= '" . $postcode . "')";
		
		
		$select = $read->select()->from($table);
		$select->where(
				$servicesString.
				$searchString
			);
		
		$newdata=array();
		$row = $read->fetchAll($select);
		if (!empty($row))
		{
			foreach ($row as $data) {
				$newdata[]=$data;
			}
		}
		return $newdata;
    }
    
    /**
     * Lista os registros desatualizados com base nos servi�os, frequencia e limite
     * @param array $postMethods
     * @param int $frequency
     * @param int $limit
     * @return array
     */
    public function listServices($postMethods, $frequency, $limit) {
    	$read = $this->_getReadAdapter();
    	$table = Mage::getSingleton('core/resource')->getTableName('av5_correios/correios');
		
    	$select = $read->select()->from($table);
    	$select->where("(lastupdate IS NULL OR lastupdate < SUBDATE(NOW(),".$frequency.")) AND servico in (".$postMethods.")");
    	$select->limit($limit);
    	
    	return $read->fetchAll($select);
    }
    
    /**
     * Lista os serviços que precisam de atualização junto com a quantidade de registros
     * desatualizados
     * @param arra $postMethods
     * @param int $frequency
     * @return array
     */
    public function toUpdate($postMethods, $frequency) {
    	$read = $this->_getReadAdapter();
    	$table = Mage::getSingleton('core/resource')->getTableName('av5_correios/correios');
    	
    	$select = $read->select()->from($table,array("servico","count(valor) as total"));
    	$select->where("(lastupdate IS NULL OR lastupdate < SUBDATE(NOW(),".$frequency.")) AND servico in (".$postMethods.")");
    	$select->group("servico");

    	return $read->fetchAll($select);
    }
    
    /**
     * Retorna a quantidade de registros desatualizados de um servi�o
     * @param string $postMethod
     * @param int $frequency
     * @return array
     */
    public function hasToUpdate($postMethod, $frequency) {
    	$read = $this->_getReadAdapter();
    	$table = Mage::getSingleton('core/resource')->getTableName('av5_correios/correios');
    	 
    	$select = $read->select()->from($table,array("servico","count(valor) as total"));
    	$select->where("(lastupdate IS NULL OR lastupdate < SUBDATE(NOW(),".$frequency.")) AND servico = ".$postMethod);
    	$select->group("servico");
		
    	return $read->fetchRow($select);
    }
    
    /**
     * Retorna a quantidade de registros atualizados de um serviço
     * @param string $postMethod
     * @param int $frequency
     * @return array
     */
    public function updatedCount($postMethod, $frequency) {
    	$read = $this->_getReadAdapter();
    	$table = Mage::getSingleton('core/resource')->getTableName('av5_correios/correios');
    
    	$select = $read->select()->from($table,array("servico","count(valor) as total"));
    	$select->where("lastupdate >= SUBDATE(NOW(),".$frequency.") AND servico = ".$postMethod);
    	$select->group("servico");
    	
    	return $read->fetchRow($select);
    }
    
    /**
     * Verifica se o serviço está presente no banco de dados
     * @param string $service
     * @return boolean
     */
    public function isPopulated($service) {
    	$read = $this->_getReadAdapter();
    	$table = Mage::getSingleton('core/resource')->getTableName('av5_correios/correios');
    	 
    	$select = $read->select()->from($table,array("count(valor) as total"));
    	$select->where("servico = ".$service);
    	
    	$result = $read->fetchRow($select);
    	if (!$result['total']) {
    		return false;
    	}
    	
    	return true;
    }
    
    /**
     * Atualiza o serviço informado com os dados recebidos
     * @param int $id
     * @param array $data
     */
    public function updateService($id, $data) {
    	$write = $this->_getWriteAdapter();
    	$table = Mage::getSingleton('core/resource')->getTableName('av5_correios/correios');

    	$rows = $write->update($table, $data, "id = " . $id);
    }
    
    /**
     * Exclui o serviço informado
     * @param int $id
     */
    public function deleteService($id) {
    	$write = $this->_getWriteAdapter();
    	$table = Mage::getSingleton('core/resource')->getTableName('av5_correios/correios');
    
    	$rows = $write->delete($table, "id = " . $id);
    }
    
    /**
     * Limpa a tabela de preços
     */
    public function cleanDatabase() {
    	$write = $this->_getWriteAdapter();
    	$table = Mage::getSingleton('core/resource')->getTableName('av5_correios/correios');
    
    	$rows = $write->delete($table, "1");
    }
    
    /**
     * Popula o banco de dados com os dados padrão para os serviços informados
     * @param array $services
     * @param double $maxWeight
     * @param string $from
     */
    public function populate($services, $from) {
    	$read = $this->_getReadAdapter();
    	$write = $this->_getWriteAdapter();
    	$table = Mage::getSingleton('core/resource')->getTableName('av5_correios/correios');
    	
    	foreach ($services as $service) {
    		if ($service[0] == 81019) {
    			$defaultData = $this->_81019data;
    		} else {
    			$defaultData = $this->_defaultData;
    		}
    		foreach ($defaultData as $record) {
    			$select = $read->select()->from($table,array("max(peso) as total"));
    			$select->where("servico = ".$service[0]);
    			$select->where("regiao like '".$record[0]."'");
    			$result = $read->fetchRow($select);
    			
    			if (!$result['total']) {
    				$w = $service[4];
    			} else {
	    			$w = ceil($result['total'])+1;
	    			if(!$w) $w = $service[4];
    			}
    			
    			if ($w > $service[3])
    				continue;
    			
    			for($weight = $w; $weight <= $service[3]; $weight++) {
	    			try {
	    				$write->insert($table, array(
		    				'servico' 			=> $service[0],
		    				'nome'				=> $service[1],
		    				'regiao'			=> $record[0],
		    				'prazo'				=> $service[2],
		    				'peso'				=> $weight,
		    				'valor'				=> '0.00',
		    				'cep_origem'		=> $from,
		    				'cep_destino_ini'	=> $record[1],
		    				'cep_destino_fim'	=> $record[2],
		    				'lastupdate'		=> 'NULL',
		    				'cep_destino_ref'	=> $record[3]
		    			));
	    			} catch (Exception $e) {
	    				Mage::helper('av5_correios')->log("AV5_Correios Erro: " . $e->getMessage() . " > Serviço: " . $service[1] . "(" . $service[0] . ") - CEP:" . $record[1] . " a " . $record[2] . " - Peso: " . $weight);
	    			}
	    			
	    			# Correção para registrar pesos abaixo de 1Kg
	    			if ($weight < 1) {
	    				$weight = 0;
	    			}
    			}
    		}
    	}
    }
}
