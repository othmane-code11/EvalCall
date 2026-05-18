# 📞 Digitalisation du processus d’évaluation des conseillers – Centre de Relation Client

## 📌 Description du projet

Ce projet consiste à développer une application web permettant de digitaliser le processus d’évaluation des conseillers dans un Centre de Relation Client.

L’objectif principal est de remplacer le système manuel basé sur Excel, papier et signatures physiques par une plateforme centralisée, moderne et sécurisée.

L’application permet :

* la création d’évaluations des appels
* le suivi des performances des conseillers
* la gestion des grilles d’évaluation
* l’ajout des commentaires et signatures électroniques
* l’analyse des résultats via des tableaux de bord et rapports

---

# 🎯 Objectifs du projet

* Digitaliser le processus d’évaluation
* Centraliser les données
* Réduire les erreurs manuelles
* Améliorer le suivi des performances
* Standardiser les pratiques d’évaluation
* Faciliter le processus de validation
* Générer des statistiques et rapports détaillés

---

# 👥 Utilisateurs

## 👨‍💼 Manager

Le manager peut :

* créer une évaluation
* remplir la grille d’évaluation
* attribuer les scores
* ajouter des commentaires
* joindre un fichier audio
* signer l’évaluation
* consulter les statistiques et rapports
* suivre les performances des conseillers

---

## 👨‍💻 Conseiller

Le conseiller peut :

* consulter ses évaluations
* voir les scores et commentaires
* écouter les enregistrements audio
* ajouter un commentaire
* signer électroniquement l’évaluation
* suivre l’évolution de ses performances

---

# ⚙️ Fonctionnalités principales

## 🔐 Authentification & rôles

* Connexion sécurisée
* Gestion des rôles :

  * Manager
  * Conseiller
* Contrôle des accès

---

## 📋 Gestion des évaluations

* Création d’évaluations
* Gestion des appels entrants et sortants
* Gestion des sections et critères
* Gestion des critères KO
* Calcul automatique des scores

---

## 📎 Gestion des fichiers audio

* Upload des enregistrements
* Lecture intégrée dans l’application
* Stockage sécurisé

---

## ✍️ Signatures électroniques

* Signature du manager
* Signature du conseiller
* Validation des évaluations

---

## 📊 Dashboard Manager

* Statistiques globales
* Liste des évaluations
* Filtres et recherche
* Analyse des performances
* Reporting avancé

---

## 👨‍💻 Dashboard Conseiller

* Historique des évaluations
* Scores personnels
* Commentaires manager
* Notifications
* Signature électronique

---

## 📈 Reporting & statistiques

* Moyenne des scores
* Analyse des KO
* Classement des conseillers
* Évolution des performances
* Export Excel / CSV / PDF

---

# 🧱 Technologies utilisées

## Backend

* Laravel
* PHP

## Frontend

* Blade
* Bootstrap / Tailwind CSS
* JavaScript

## Base de données

* MySQL

## Outils complémentaires

* Laravel Storage
* Signature Pad
* Chart.js

---

# 🗂️ Structure de la base de données

## Tables principales

* users
* evaluations
* sections
* criteres
* reponses
* commentaires
* signatures

---

# 🔄 Workflow du système

1. Le manager crée une évaluation
2. Il sélectionne le conseiller
3. Il complète la grille d’évaluation
4. Il ajoute le fichier audio
5. Il valide et signe l’évaluation
6. Le conseiller reçoit une notification
7. Le conseiller consulte l’évaluation
8. Le conseiller ajoute un commentaire
9. Le conseiller signe électroniquement
10. L’évaluation passe au statut “Signé”

---

# 🔒 Sécurité

* Authentification sécurisée
* Gestion des rôles et permissions
* Protection des données utilisateurs
* Accès restreint aux évaluations
* Accès sécurisé aux fichiers audio

---

# 🚀 Installation du projet

## 1. Cloner le projet

```bash
git clone <repo-url>
```

## 2. Accéder au dossier

```bash
cd nom-du-projet
```

## 3. Installer les dépendances

```bash
composer install
```

---

## 4. Configurer le fichier .env

```bash
cp .env.example .env
```

Configurer :

* DB_DATABASE
* DB_USERNAME
* DB_PASSWORD

---

## 5. Générer la clé Laravel

```bash
php artisan key:generate
```

---

## 6. Lancer les migrations

```bash
php artisan migrate
```

---

## 7. Lancer le serveur

```bash
php artisan serve
```

---

# 📌 Statuts des évaluations

| Statut    | Description                         |
| --------- | ----------------------------------- |
| Brouillon | Évaluation en cours                 |
| Complété  | Évaluation validée par le manager   |
| Signé     | Évaluation signée par le conseiller |

---



# 👨‍💻 Auteur

Projet réalisé par :

**Yahya Irfane**
**othmane ezzaytouni**

---

# 📄 Licence

Projet académique / pédagogique.
