# Transport Calculator

## Prérequis

Comme prérequis, il faut **PHP** dans sa version **8.1** minimum, **composer** et **Node.Js** dans sa dernière version **lts**
## Installation
Pour installer les dépendances du projet, vous allez pater les commandes suivantes à la racine du projet.

    composer install
    
    npm install ou yarn 

Pour générer la clé de cryptage du projet, vous allez d'abord cloner le fichier d'environnement avec la commande suivante

    cp .env.example .env

Vous allez taper la commande suivant pour générer la clé

    php artisan key:generate

Dans le fichier **.env**, l'attribue **APP_URL** doit être égale à **http://localhost:8000**

Le projet utilise **Vue.js 3** en frontend pour la première utilisation, nous allons d'abord générer les fichiers JavaScript.

    npm run dev ou yarn dev
    
    npm run build ou yarn build

Pour démarrer le projet, c'est :

    php artisan serve





