# Demarer avec Symfony

## 1 - Installation

### Composer

Verifier la version de composer :

```bash
composer --version
```

Si **composer** n'est pas installer aller sur le site [https://getcomposer.org/](https://getcomposer.org/) pour suivre le tuto proposé, dans l'onglet Download.

### PHP

Si **php** n'est pas installer sur Windows alors :

- Aller sur la page [https://windows.php.net/download/](https://windows.php.net/download/) pour télécharger le zip de la version voulu de php pour windows (VC15 x64 Thread Safe avec 64 bits de Windows)
- Décompresser les fichiers ZIP dans un répertoire au choix : par exemple : `C:\php` pour en extraire les fichiers.
- Copier le fichier `php.ini-development` (ou `php.ini-production` si on veut une configuration de production) se trouvant dans le dossier php créé pour le renommer en `php.ini`.
- Ouvrez le fichier pour le configurer selon nos besoins : décommenter les extensions voulues.

```ini
extension=mysql
extension=mysqli
extension=intl
extension=curl
extension=pdo_mysql
extension=openssl
extension=pdo_pgsql
extension=pdo_sqlite
extension=pgsql
extension=sqlite3
```

- Ajoutez PHP au PATH pour pouvoir executer PHP dans tous les répertoires de l'ordinateur :

  1. Faire un clic droit sur `Ce PC -> Propriétés -> Paramètres avancés du système -> Variables d'environnement...`
  2. On va avoir la liste des variables utilisateurs et systèmes. Trouvez la variable `Path` et cliquez sur `Modifier`
  3. Ajout le chemin complet du dossier PHP : `C:\php` et appuyez sur ok les deux fois

- Testez PHP avec la commande

```bash
php --version
```

Vous devez avoir isntaller php.

Tentez ou retentez l'installation de composer avec les lignes de commandes suivantes :

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

### Symfony CLI

#### Scoop

Si **Scoop** n'est pas installer alors :

- Ouvrir un terminal `PowerShell`et ecrire :

```bash
> Set-ExecutionPolicy RemoteSigned -Scope CurrentUser # Optional: Needed to run a remote script the first time
```

- Puis ecrire :

```bash
irm get.scoop.sh | iex
```

#### Symfony

Puis dans le terminal bash écrire :

```bash
scoop install symfony-cli
```

## 2 - Démarrage du projet symfony

### Nouveau projet

```bash
symfony new --webapp gestion-tournois-sportif
```

On peut également choisir la version installer mais elle cause des problème en focntion des versions des autres composents comme `php` ou `composer`.

Pour verifier la version installer pour le projet dans le dossier :

```bash
cd my-project
symfony console about
```

### Configuration du projet

Pour voir la liste des recommandations pour faire fonctionner Symfony :

```bash
symfony check:requirements
```

On verifie les versions de chaque composants utiles (-V, --version) :

- MySQL
- Composer
- Symfony CLI
- PHP

Puis on va configurer l'environnement :

- Pour sela trouver le fichier `.env` et l'enregistrer sous en fichier `.env.local`

- Ouvrir le fichier et modifier les lignes celon nos besoins :

  - **MESSENGER_TRANSPORT_DSN** : méthode de transport des messages (mails, notifications…)
  - **DATABASE_URL** : paramétrage de la connexion à la base de données (pilote, utilisateur, mot de passe, hôte, nom de la base de données, version du serveur)
    - Commente la ligne par default (POSTGRSQL)
    - Decommente la ligne sur MySQL
    - Modifie la ligne pour avoir :
    ```env
    DATABASE_URL="mysql://root@localhost/gestion-tournois-sportif"
    ```
  - **MAILER_DSN** : serveur utilisé pour l'envoi de mails

    - installer `mailer` avec `composer` :

    ```bash
    composer require symfony/mailer
    ```

    - modifier dans le fichier en le configurant `.env.local` :

    ```env
    MAILER_DSN=smtp://localhost:1025
    ```

### Lancer le serveur

```bash
  symfony serve -d
```

Puis on veut sécuriser le serveur donc on lance :

```bash
  symfony server:ca:install
```

## Mettre en place la BDD

### Construire la base

Ecrire cette ligne dans un terminal :

```bash
symfony console doctrine:database:create
```

Si cela ne marche pas essayer avant :

```bash
symfony console cache:clear
```

#### Utilisateur

Creation de la table Users avec

```bash
symfony concole make:user
```

Il va alors demander des informations additionnels :

- Nom de la table : `Users`
- Stocker les use dans la bdd ? : `yes` (default)
- Champ de connexion : `email` (default)
- Hasher les mot de passe ? `yes` (default)

Cela va creer les fichiers `src/Entity/Users.php` et `src/Repository/UsersRepository.php` et mettre à jour les fichiers `src/Entity/Users.php` et `config/packages/security.yaml`.

On a donc les attriuts de tous les user habituel ainsi que les accesseurs qui vont avec dans le fichier `Users.php`.

On va donc modifier l'entité `User` pour ajouter les autres propriétés de la table sachant que on a déjà l'id, l'email, et les roles de l'utilisateur :

```bash
symfony concole make:entity
```

Il demande alors :

- Nom de la classe qu'on veut modifier : `Users`

1. Nom de la propriété : `nomUser`

   - Type d'attribut : `string` (default)
   - Taille de l'attribut : `100`
   - Est ce qu'il peut être nul ? `no` (default)

2. **Nouvelle propriété** : `prenomUser`

- Type d'attribut : `string` (default)
- Taille de l'attribut : `100`
- Est ce qu'il peut être nul ? `no` (default)

3. **Nouvelle propriété** : `villeUser`

- Type d'attribut : `string` (default)
- Taille de l'attribut : `150`
- Est ce qu'il peut être nul ? `no` (default)

4. **Nouvelle propriété** : `created_at`

- Type d'attribut : `datetime_immuable` (default)
- Est ce qu'il peut être nul ? `no` (default)

5. **Nouvelle propriété** : `sports`

- Type d'attribut : `array`
- Est ce qu'il peut être nul ? `no` (default)

Puis entrer pour valider et cela va mettre à jour `src/Entity/Users.php`.

Dans le fichier `src/Entity/Users.php` met à jour la ligne 39 avec l'option pour que la date par default soit la date courante de creation du user :

```php
#[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;
```

De plus modifie la ligne suivant :

```php
#[ORM\Column(type: Types::ARRAY)]
    private array $sports = [];
```

En (enlever le type dans la paranthèse pour obtenir) :

```php
#[ORM\Column]
    private array $sports = [];
```

On va alors faire une migration pour mettre à jour la base de données :

```bash
symfony console make:migration
symfony console doctrine:migrations:migrate
```

-> Confirmation : `yes` (default)

Ca nous à donc créé la table `users` dans la base de données et il ya également la table pour les migrations et les messages que l'on utilisera plus tard.

Ensuite on doit créer toutes les autres tables voici la démarche en exemple :

#### NomTable

```bash
symfony concole make:entity
```

Dans les informations demandées :

- Nom de l'entité : `Roles`

1. **Nouvelle propriété** : `nomRole`

- Type d'attribut : `string` (default)
- Taille de l'attribut : `100`
- Est ce qu'il peut être nul ? `no` (default)

#### Sports

```bash
symfony concole make:entity
```

Dans les informations demandées :

- Nom de l'entité : `Sports`

1. **Nouvelle propriété** : `nomSport`

- Type d'attribut : `string` (default)
- Taille de l'attribut : `100`
- Est ce qu'il peut être nul ? `no` (default)

2. **Nouvelle propriété** : `nbJoueur`

- Type d'attribut : `integer`
- Est ce qu'il peut être nul ? `no` (default)

#### Rencontres

```bash
symfony concole make:entity
```

Dans les informations demandées :

- Nom de l'entité : `Rencontres`

1. **Nouvelle propriété** : `dateTime_at`

- Type d'attribut : `datetime` (default)
- Est ce qu'il peut être nul ? `no` (default)

2. **Nouvelle propriété** : `poule`

- Type d'attribut : `integer`
- Est ce qu'il peut être nul ? `yes`

3. **Nouvelle propriété** : `tourJournee`

- Type d'attribut : `integer`
- Est ce qu'il peut être nul ? `yes`

4. **Nouvelle propriété** : `gagnant`

- Type d'attribut : `relation`
- Quel table on associe ? : `Equipes`
- Quel genre de relation : `ManyToOne` (une équipe à un seul capitaine et un capitaine n'est le capitaine que d'une seule équipe)
- Est ce qu'il peut être nul ? `yes` (default)
- Est ce que on ajoute dans Equipe ? `yes` (default)
- Sous quel nom ? `gagnantRencontre`

#### Equipes

```bash
symfony concole make:entity
```

Dans les informations demandées :

- Nom de l'entité : `Equipes`

1. **Nouvelle propriété** : `nomEquipe`

- Type d'attribut : `string` (default)
- Taille de l'attribut : `100`
- Est ce qu'il peut être nul ? `no` (default)

2. **Nouvelle propriété** : `niveau`

- Type d'attribut : `integer`
- Est ce qu'il peut être nul ? `no` (default)

3. **Nouvelle propriété** : `adresseMail`

- Type d'attribut : `string` (default)
- Taille de l'attribut : `150`
- Est ce qu'il peut être nul ? `no` (default)

4. **Nouvelle propriété** : `numeroTel`

- Type d'attribut : `string` (default)
- Taille de l'attribut : `10`
- Est ce qu'il peut être nul ? `no` (default)

5. **Nouvelle propriété** : `capitaine`

- Type d'attribut : `relation`
- Quel table on associe ? : `Users`
- Quel genre de relation : `OneToOne` (une équipe à un seul capitaine et un capitaine n'est le capitaine que d'une seule équipe)
- Est ce qu'il peut être nul ? `no`
- Est ce que on ajoute dans Users ? `yes`
- Sous quel nom ? `capitaineEquipe`

6. **Nouvelle propriété** : `sport`

- Type d'attribut : `relation`
- Quel table on associe ? : `Sports`
- Quel genre de relation : `ManyToOne` (un sport peut aller à plusieurs équipes mais une équipes ne peut aller qu'a un seul sport)
- Est ce qu'il peut être nul ? `no`
- Est ce que on ajoute dans Sports ? `yes` (default)
- Sous quel nom ? `sportEquipes`
- Est ce que si on supprime les sports, les equipes se supprime aussi ? `yes`

7. **Nouvelle propriété** : `membres`

- Type d'attribut : `relation`
- Quel table on associe ? : `Users`
- Quel genre de relation : `ManyToMany` (utilisateur peut etre dans plusieurs equipe et les equipes sont composées de plusieurs use)
- Est ce que on ajoute dans Users ? `yes` (default)
- Sous quel nom ? `membreEquipes`

8. **Nouvelle propriété** : `concourtTournoi`

- Type d'attribut : `relation`
- Quel table on associe ? : `Tournois`
- Quel genre de relation : `ManyToMany` (utilisateur peut etre dans plusieurs equipe et les equipes sont composées de plusieurs use)
- Est ce que on ajoute dans Tournois ? `yes` (default)
- Sous quel nom ? `listeEquipes`

9. **Nouvelle propriété** : `participeRencontre`

- Type d'attribut : `relation`
- Quel table on associe ? : `Rencontres`
- Quel genre de relation : `ManyToMany` (utilisateur peut etre dans plusieurs equipe et les equipes sont composées de plusieurs use)
- Est ce que on ajoute dans Tournois ? `yes` (default)
- Sous quel nom ? `listeEquipes`

#### Rencontres

```bash
symfony concole make:entity
```

Dans les informations demandées :

- Nom de l'entité : `Rencontres`

1. **Nouvelle propriété** : `dateTime_at`

- Type d'attribut : `datetime`
- Est ce qu'il peut être nul ? `no` (default)

2. **Nouvelle propriété** : `gagnant`

- Type d'attribut : `relation`
- Quel table on associe ? : `Equipes`
- Quel genre de relation : `ManyToOne` (une équipe gagne à plusieurs rencontres et une rencontre ne peut etre gagner que par une seule equipe)
- Est ce qu'il peut être nul ? `yes` (default)
- Est ce que on ajoute dans Equipes ? `Yes`
- Sous quel nom ? `rencontresEquipes` (default)

3. **Nouvelle propriété** : `poule`

- Type d'attribut : `integer`
- Est ce qu'il peut être nul ? `no` (default)

4. **Nouvelle propriété** : `tourJournee`

- Type d'attribut : `integer`
- Est ce qu'il peut être nul ? `no` (default)

5. **Nouvelle propriété** : `participantRencontres`

- Type d'attribut : `relation`
- Quel table on associe ? : `Equipes`
- Quel genre de relation : `ManyToMany` (une équipe participe à plusieurs rencontres et une rencontre a plusieurs participants)
- Est ce qu'il peut être nul ? `no` (default)
- Est ce que on ajoute dans Users ? `Yes`
- Sous quel nom ? `equipes` (default)

#### Tournois

```bash
symfony concole make:entity
```

Dans les informations demandées :

- Nom de l'entité : `Tournois`

1. **Nouvelle propriété** : `nomTournoi`

- Type d'attribut : `string`
- Taille de l'attribut : `100`
- Est ce qu'il peut être nul ? `no` (default)

2. **Nouvelle propriété** : `startTime_at`

- Type d'attribut : `datetime` (default)
- Est ce qu'il peut être nul ? `no` (default)

3. **Nouvelle propriété** : `dureeTournoi`

- Type d'attribut : `integer`
- Est ce qu'il peut être nul ? `no` (default)

4. **Nouvelle propriété** : `adressePostal`

- Type d'attribut : `string`
- Taille de l'attribut : `150`
- Est ce qu'il peut être nul ? `no` (default)

5. **Nouvelle propriété** : `codePostal`

- Type d'attribut : `string`
- Taille de l'attribut : `5`
- Est ce qu'il peut être nul ? `no` (default)

6. **Nouvelle propriété** : `villeTournoi`

- Type d'attribut : `string`
- Taille de l'attribut : `100`
- Est ce qu'il peut être nul ? `no` (default)

6. **Nouvelle propriété** : `paysTournoi`

- Type d'attribut : `string`
- Taille de l'attribut : `100`
- Est ce qu'il peut être nul ? `no` (default)

7. **Nouvelle propriété** : `nbEquipes`

- Type d'attribut : `integer`
- Est ce qu'il peut être nul ? `no` (default)

8. **Nouvelle propriété** : `sportTournoi`

- Type d'attribut : `relation`
- Quel table on associe ? : `Sports`
- Quel genre de relation : `ManyToOne` (un tournoi n'a qu'un seul sport et un sport peut apartenir à plusieurs tournois)
- Est ce qu'il peut être nul ? `no`
- Est ce que on ajoute dans Sport ? `yes` (default)
- Sous quel nom ? `sportTournois`
- Est ce que si on supprime Sport on supprime les tournois associés ? `yes`

9. **Nouvelle propriété** : `etatTournoi`

- Type d'attribut : `string`
- Taille de l'attribut : `100`
- Est ce qu'il peut être nul ? `no` (default)

10. **Nouvelle propriété** : `typeTournoi`

- Type d'attribut : `string`
- Taille de l'attribut : `100`
- Est ce qu'il peut être nul ? `no` (default)

11. **Nouvelle propriété** : `listeGestionnaires`

- Type d'attribut : `relation`
- Quel table on associe ? : `Users`
- Quel genre de relation : `ManyToMany` (un use peut etre gestionnaire de plrs tournoi et les tournois peuvent avoir plrs gestionnaire)
- Est ce que on ajoute dans Sport ? `yes` (default)
- Sous quel nom ? `gestionnaireTournois`

12. **Nouvelle propriété** : `listeRencontres`

- Type d'attribut : `relation`
- Quel table on associe ? : `Recnontres`
- Quel genre de relation : `OneToMany` (une rencontre etre dans un seul tournois et un tournois peut avoir plsuieurs rencontres)
- Sous quel nom ? `appartenanceTournoi`
- Null ? `no`
- Si les tournois se supprime est ce que les rencotnres associé aussi ? `yes`

13. **Nouvelle propriété** : `vainqueur`

- Type d'attribut : `relation`
- Quel table on associe ? : `Equipes`
- Quel genre de relation : `ManyToOne` (un vainqueur par tournois mais une equipe peut gagner plsueirus tournois)
- Peut être null ? `yes` (default)
- Ajout dans Equipes ? `yes` (default)
- Sous quel nom ? `vaiqueurTournois`

### Mettre à jour la base

On fait ensuite une migration pour mettre à jour la base :

```bash
symfony console make:migration
symfony console doctrine:migrations:migrate
```

## Enlever WebPack

```bash
console remove webpack
```

## 3 - Creer un controller de base

```bash
symfony console make:controller MainController
```

Cela va creer un dossier `src/Controller/MainController.php` ainsi que `templates/main/index.html.twig` qui vont être le controller du main et l'affichage. Par default, les controllers étend la classe `AbstractController`.
Si on veut regarder la page du `MainController` on a juste à aller sur notre navigateur et ajouter `/main` à l'URL du navigateur pour faire aparraitre le fait que on a un controller qui s'apelle `MainController`.

On va alors modifier l'URL en modifiant :

```php
#[Route('/main', name: 'app_main')]
```

en

```php
#[Route('/', name: 'main')]
```

On aura donc besoin pour chaque page d'un nouveau controller.

On va faire un rendu dans `templates/main/index.html.twig` directement. Cela va fonctionner par twig et on enleve les paramètres dans le fichier `MainController.php`telque :

```php
return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
```

deviennent ça :

```php
return $this->render('main/index.html.twig');
```

Puis pour que cela marche on doit enelever ce que contient la balise body du fichier `index.html.twig` pour le remplacer par :

```twig
{% block body %}
<p>Accueil</p>
{% endblock %}
```

Qui n'est simplement qu'un exemple.

## 4 - Creation du fichier base.html.twig

On peut ensuite voir le fichier `templates/ase.html.twig` pour modifier la page de référence :

On vide les différents block pour pouvoir les rmeplir comme on veut.
Tout ce que je rajoute dans les controler ici `main/index.html.twig` remplacera la base à condition qu'on a la ligne :

```twig
{% extends 'base.html.twig' %}
```

Il manque une balise meta pour indiquer que le site est responsive dans le fichier `base.html.twig`:

```html
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
```

## Ajout des assets

On va pouvoir injecter des fichiers CSS et des fichiers JS en créant un nouveau dossier dans le dossier `public` ainsi on aura la structure :

```
public
| css
| | fichier.css
| | ...
| js
| | fichier.js
| | ...
| index.php
```

Pour pouvoir ajouter des fichiers dans les dossiers correspondant.

Mais ici nosu allons utiliser `bootstrap ` :

### Récuperation de Bootstrap

- Aller sur le site suivant : [https://getbootstrap.com/docs/5.3/getting-started/download/](https://getbootstrap.com/docs/5.3/getting-started/download/) pour télécharger les fichiers css et js.

- Décompresser les fichiers

- Puis récuperer les fichiers `bootstrap.min.css` et `bootstrap.min.css.map` ainsi que les fichiers `bootstrap.bundle.js` et `bootstrap.bundle.js.map` et les mettre dans les dossiers correspondant.

- Ensuite on les ajoutes au fichier `base.html.twig` avant le block `stylesheets` pour montrer que ce c'estun fichier que j'utilise pour tous les autres fichiers. De plus, `asset` permet d'aller chercher un fichier dans le dossier `public` par lui même :

```html
<!-- Feuille de styles -->
<link
  rel="stylesheet"
  href="{{ asset('assets/css/bootstrap.min.css')}}"
/><!-- Feuille de styles -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}" />
```

Cela va changer la police de caractère pour bien voir que ça à marché.

- On va maintenant rajoute les autres styles css en dessous du block `stylesheets`. Cela va permettre que mon style surcharge ce que je met dans le block et ce qu'il y a dans le bloc va surcharger le css de bootstrap.

- De la même facon on le fait sur le balise `javascripts` en ajoutant `defer` qui va declencher le js après que le DOM soit charger. On doit donc avoir :

```html
<!-- Feuille de styles -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}" />
{% block stylesheets %} {% endblock %}
<link rel="stylesheet" href="{{ asset('assets/css/styles.css')}}" />

<!-- Script JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}" defer></script>
{% block javascripts %} {% endblock %}
<script src="{{ asset('assets/js/sript.js')}}" defer></script>
```

Vous vérifiez que mes fichiers ce sont bien importer et charger on peut voir dans l'inspecteur du navigateur qu'il y a dans l'onglet `Reseau` les fichiers qui ce sont chargés.

## Creation d'une barre de navigation

On va sur le site de bootstrap pour avoir la barre de navigation de base [https://getbootstrap.com/docs/5.3/components/navbar/](https://getbootstrap.com/docs/5.3/components/navbar/) que nous mettons dans le fichier `base.html.twig` pour obtenir une barre de navigation permanente sur tous les pages de navigation.

Puis on ajoute une balise footer telque pour avoir 3 colonnes :

```html
<footer class="container-fluid bg-light">
			<div class="row">
				<section class="col">
					Colonne 1
				</section>
				<section class="col">
					Colonne 2
				</section>
				<section class="col">
					Colonne 3
				</section>
			<div>
		</footer>
```

Comme le fichier `base.html.twig` n'est pas forcement fait pour contenir autant de code alors on va créer des fichiers correspondant aux composents :

- Creation d'un dossier `templates/_partials`

- Creation d'un novueau fichier
  `templates/_partials/_nav.html.twig` et `templates/_partials/_footer.html.twig`

- Copier coller les codes de chaque composents dans les fichiers correspondant

- Inclure les fichiers dans `base.html.twig` en mettant avant le block `body` la navbar et après le footer comme ceci :

```twig
{% include "_partials/_nav.html.twig"%}

{% block body %}{% endblock %}

{% include "_partials/_footer.html.twig"%}
```

Si jamais on met les styles dans le block dans le fichier `base.html.twig` alors on doit chercher le block parent en mettant dans les autres fichier twig telque :

```twig
{{ parent()}}
```

## 5 - Créer l'authentification de l'utilisateur

### Formulaire de connection

```bash
symfony console make:auth
```

- Quel système d'authentification ? `1` (pour avoir le formulaire)
- Nom de l'authentification : `UsersAuthenticator`
- Nom du controller de connexion et deconnexion : `SecurityController`(default)
- Generer le /logout ? `yes` (default)

Cette ligne de commande va donc créer les fichiers `src/Security/UsersAuthenticator.php`, `src/Controller/SecurityController.php` et le fichier `templates/security/login.html.twig` et il a mis à jour le fichier `config/packages/security.yaml`.

- La mise à jour à ajouter une partie qui gère le nom de l'authenticator qui va donner la route ainsi que la redirection une fois déconnecté.

- La création de `SecurityController` permet de contenir la route de connexion on modifie alors `login` en `connexion` pour que ce soit en français au niveau de la route.

- Celle de `UsersAuthenticator.php` permet d'avoir les méthode de connexion et récuperation d'URL et la gestion de l'authentification. A la ligne 51 on remplace :

```php
//return new RedirectResponse($this->urlGenerator->generate('some_route'));
throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
```

par,

```php
return new RedirectResponse($this->urlGenerator->generate('main'));
//throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
```

Cela va rediriger les utilisateur une fois qu'il se connecte à la page de route `main`.

On peut alors verifier en ajoutant à l'URL du navigateur `/connexion` qu'on a bien une page d'authentification.

On modifie ensuite le fichier `templates/security/login.html.twig` à notre convenance.

```twig
<section class="container">
    <div class="row">
        <div class="col">
          <!--Code du formulaire-->
        </div>
    </div>
</section>
```

### Formulaire d'enregistrement

```bash
symfony console make:registration-form
```

- UniqueEntity pour pas avoir de doublons ? `yes`(default)
- Envoie d'un email après enregistrement ? `no`
- Auto connecté directement ? `yes`

Il a donc créé et mis à jour des fichiers :

- Nouveau controller qui s'appel `src/Controller/RegistrationController.php` qui permet l'enregistrement dans la base de données des utilisateurs. On modifie la route de `register` en `inscription`.

- Ajout d'une annotation dans le fichier `src/Entity/Users.php` qui nous dit si l'email est déjà utilisé qu'il faut en choisir un autre.

- Fichier de formulaire : `src/Form/RegistrationFormType.php`

````

Pour ajouter la date d'aujourd'hui sur l'affichage on utilisera :

```twig
 {{"now" | date('d/m/Y H:i', timezone="Europe/Paris")}}
```

L'idée est d'utiliser `{{ }}` pour faire référence à un élément associé à PHP.
````

Pour selectionner les tuples dans une table on peut faire :

```bash
symfony console doctrine:query:sql "select * from users"
```

#### Profilage et debugage

```bash
composer require –dev symfony/profiler-pack
```

Pour avoir la barre noir en bas qui est un bundle permettant d'avoir des informations sur le développement dans l'appli.

- Installer l'extension sur VSCode `PHP Xdebug` :

  - Ajouter l'extension sur le navigateur de `Xdebug helper` qui a pour icon un insecte gris qui devient vert si il est actif.
  - Aller dans l'onglet `Run and Debug` de VSCode
  - Creer un fichier de configuration `launch.json` et mettre unenovuelle configuration : (pris le firefox de base)

  ```json
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type":"php",
      "request": "launch",
      "port":9000
    }
  ]

  ```

  - Puis en haut à gauche de la vue debug, cliquer sur le triangle vert pour lancer le serveur effectuant `Listen for Xdebug`
  - On va lancer un controler PHP sur le navigateur et mettre un point d'arret
  - On aura donc une barre de debug et les variales tout au long du code

  #### Doctrine

Création de la base de données :

```bash
symfony console doctrine:database:create
```

Ligne de commande sur doctrine :

```bash
symfony console doctrine:query:sql "create table essai(nom varchar(10));"
symfony console doctrine:query:sql "select * from essai"
```
