# message-handler

![](doc/screenshot.jpg)

## Contexte

Voir le sujet [ici](https://github.com/acseo/contact-form)

## Conditions requises
* PHP >= 7.1
* MySQL >= 5.7

## Installation du projet dans votre environnement local
Cloner le projet.

```
git clone https://github.com/dev-tsf4/message-handler.git
```

Accéder au répertoire du projet, récupérer les dépendances avec composer.

```
composer install
```

Editer le fichier .env à la racine du projet et changer les paramètres d'accès à votre base de données. 

```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```
Lancer le script suivant, afin de construire la base de données, créer les tables, charger les fixtures :
```
composer prepare-dev-env
```
Ou chaque commande une par une :
```
php bin/console doctrine:database:drop --if-exists --force
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load --no-interaction
```
Lancer votre serveur

## Application

### Administrateurs
  
| Email        | Password           | Roles  |
| --- |---| ---|
| admin@mail.com      | admin | ROLE_ADMIN |
| johndoe@mail.com      | secret123      |   ROLE_ADMIN |

Créer votre propre administrateur :
```
php bin/console app:create-administrator [email] [password]
```
### Liste des URLs

- /contact
- /connexion
- /admin

Upload des fichiers Json dans le répertoire /var/files/messages

## Explication de certains choix de développement

- J'ai souhaité utiliser toute la 'magie' du framework afin de mettre le moins de logique métier dans les Controllers : utilisation des évènements de formulaire, EventSubscriber, utilisation de service (ex: Storage), ... 
- Le test précise que l'administrateur(webmaster) doit pouvoir cocher les messages traités. Il me semble que la solution d'utiliser les évènements du formulaire(FormEvents::PRE_SET_DATA) est plus 'propre' pour l'édition du message. Toujours dans le but de faire moins de logique dans le controller.
- Concernant les demandes regroupés, j'envoie directement le tableau correctement formaté. Afin de faciliter l'expérience utilisateur en nombre de clique.
- Dans toutes mes applications, je préfère créer une classe Administrator et une classe User(pas ici pour ce test), je trouve cela plus explicite à la lecture/relecture du code afin de mieux comprendre le rôle de chacun.
- Pour ce test, je ne stocke pas les rôles en BD.
- Pas de TDD, afin de rendre le projet dans un délai raisonnable.

## Refactorisation et amélioration de l'application

- Capturer certaines exceptions, notamment lors de l'écriture du fichier Json.
- Implémenter un pattern strategy pour le stockage du fichier, on peut imaginer que le client veuille par la suite d'autres formats de sortie (XML, CSV, ...)
- Utiliser une librairie comme Flysystem afin d'interagir avec de nombreux types de systèmes de fichiers (AWS S3, ...)
- Alléger les Controllers, en sortant la logique de traitement (persist, flush, la création du tableau indexé des messages), par exemple avec un MessageManager.
- Pour la partie formulaire de contact, ajout d'un captcha.