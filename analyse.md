Commentaires à propos du SDK existant.
--------------------------------------

* Lignes 44, 54 et 64, l'instance static est appelée sans vérification depuis des méthodes publiques et statiques.

* De nombreuses méthodes utilisent un système bizarre pour contacter la méthode 'requestApi' et lui fournir des données.
Elles lui envoie le nom d'une fonction interne que la méthode 'requestApi' sera chargée d'appelée afin d'obtenir des datas.
Les méthodes intiales auraient tout aussi bien pu appeler directement les fonctions privées et fournir à la méthode 'requestApi' les données dont elle avait besoin.

* Pattern singleton mal implémenté.
Le constructeur doit être marqué comme privé afin de forcer l'utilisation de la méthode getInstance.

* Le constructeur n'ayant pour vocation de n'être utilisé qu'une fois, les méthode IdsAreEmpty et setHost sont inutiles.

* Utilisation de variables en majuscule. ($UI, $CP, $HOST)

* Construction inutile : return ($data = array(...));
$data n'étant pas transmis par référence, il n'y a aucun intérêt à le redéfinir dans la fonction.

* Multiple array au format long. (array() au lieu de [])

* Possible manque du shema dans la fonction oauth_access.

* Il manque beaucoup de vérification avant la génération des URLS.

* Certaines des fonctions de génération des URLS utilisent des fonctions annexent contenant le endpoint, d'autre contiennent directement le endpoint en dur.

* Inconsistence des noms de méthods. Parfois du snake_case, parfois du camelCase.
