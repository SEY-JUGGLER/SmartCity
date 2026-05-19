# Smart City — Système Intelligent de Gestion des Signalements Urbains

## Présentation du Projet

Smart City est une plateforme intelligente de gestion des signalements urbains permettant aux citoyens de signaler des problèmes urbains en temps réel et aux autorités de superviser efficacement les interventions terrain.

Le système permet :

* la gestion des signalements citoyens
* l’attribution intelligente des interventions
* la supervision des agents terrain
* le suivi des interventions en temps réel
* la gestion des matériels et équipements
* la génération de statistiques et rapports analytiques

Le projet est conçu avec une architecture moderne utilisant Laravel 12, Filament et Livewire.

---

# Objectifs du Projet

## Objectif Général

Améliorer la gestion des problèmes urbains grâce à une plateforme numérique centralisée permettant une meilleure communication entre citoyens, agents terrain et administration.

---

## Objectifs Spécifiques

* Permettre aux citoyens de signaler rapidement des incidents
* Faciliter l’attribution des missions aux agents les plus proches
* Optimiser les interventions terrain
* Assurer le suivi des signalements en temps réel
* Fournir des statistiques et rapports décisionnels
* Améliorer la gestion des ressources humaines et matérielles

---

# Technologies Utilisées

## Backend

* Laravel 12
* PHP 8.3+
* MySQL

---

## Frontend

* Livewire
* Filament
* Tailwind CSS
* Alpine.js

---

## APIs & Services

* Google Maps API
* Geolocation API
* Distance Matrix API
* Geocoding API

---

## Outils

* Composer
* Node.js
* Vite
* Git
* XAMPP / Laragon

---

# Architecture du Projet

## Architecture Générale

Le système suit une architecture MVC moderne basée sur Laravel.

### Organisation

* Frontend : Livewire + Filament
* Backend : Laravel
* Base de données : MySQL
* Cartographie : Google Maps

---

# Rôles des Utilisateurs

## 1. Administrateur

L’administrateur supervise entièrement le système.

### Fonctionnalités principales

* gestion des signalements
* gestion des agents
* gestion des zones
* attribution des missions
* supervision des interventions
* gestion des matériels
* génération des rapports
* suivi des statistiques
* gestion des utilisateurs
* suivi temps réel
* gestion des notifications

---

## 2. Agent Terrain

L’agent est responsable des interventions terrain.

### Fonctionnalités principales

* consulter missions attribuées
* accepter/refuser missions
* commencer intervention
* terminer intervention
* mise à jour des statuts
* upload photos avant/après
* pointage quotidien
* gestion disponibilité
* demande de matériel
* demande de renfort
* consultation carte GPS
* navigation Google Maps

---

## 3. Citoyen

Le citoyen peut signaler des problèmes urbains.

### Fonctionnalités principales

* création de signalement
* upload photos
* partage position GPS
* suivi des signalements
* consultation historique
* notifications temps réel
* évaluation des interventions

---

# Fonctionnalités Principales

## Gestion des Signalements

Le système permet :

* création de signalement
* catégorisation des incidents
* ajout de description
* ajout de photos
* géolocalisation GPS
* suivi des statuts
* priorisation des urgences

---

## Attribution Intelligente

Le système attribue les missions selon :

* disponibilité des agents
* distance entre agent et signalement
* matériels disponibles
* performance des agents
* niveau de priorité

---

## Gestion des Matériels

Le système gère :

* véhicules
* équipements
* outils de nettoyage
* matériels de sécurité
* état des matériels
* affectation aux agents

---

## Gestion des Zones

Le système permet :

* création des zones
* suivi des statistiques par zone
* analyse des zones critiques
* visualisation cartographique

---

## Rapports & Analytics

Le système génère :

* rapports journaliers
* rapports hebdomadaires
* rapports mensuels
* rapports annuels
* statistiques globales
* statistiques agents
* statistiques zones
* heatmaps

---

# Fonctionnement du Système

## Étape 1 — Signalement Citoyen

Le citoyen :

1. crée un signalement
2. ajoute description et photos
3. partage sa position GPS
4. valide le signalement

---

## Étape 2 — Analyse du Signalement

Le système :

* enregistre le signalement
* détermine la priorité
* identifie la zone concernée
* calcule les agents proches

---

## Étape 3 — Attribution

L’administrateur ou le système :

* choisit l’agent adapté
* vérifie disponibilité et matériel
* attribue la mission

---

## Étape 4 — Intervention Terrain

L’agent :

* accepte la mission
* consulte l’itinéraire GPS
* effectue l’intervention
* ajoute photos avant/après
* clôture la mission

---

## Étape 5 — Clôture & Rapport

Le système :

* met à jour les statistiques
* notifie le citoyen
* archive les données
* génère les rapports

---

# Géolocalisation & Attribution

Le système utilise :

* latitude
* longitude
* formule de Haversine
* Google Maps API

pour calculer l’agent le plus proche du signalement.

---

# Structure de la Base de Données

## Tables Principales

### users

Gestion des utilisateurs.

### signalements

Gestion des incidents urbains.

### zones

Gestion des zones géographiques.

### attributions

Gestion des missions agents.

### localisations

Coordonnées GPS.

### materiels

Gestion des équipements.

### agent_materiels

Affectation des matériels.

### demandes_support

Demandes de renfort ou matériel.

### notifications

Notifications système.

### rapports

Rapports et statistiques.

---

# Structure du Projet Laravel

## Dossiers Principaux

```txt
app/
 ├── Filament/
 ├── Livewire/
 ├── Models/
 ├── Services/
 ├── Repositories/
 ├── Helpers/
 ├── Notifications/
 └── Policies/

resources/
 ├── views/
 ├── css/
 └── js/

routes/
 ├── web.php
 └── api.php
```

---

# Installation du Projet

## 1. Cloner le projet

```bash
git clone repository_url
```

---

## 2. Installer les dépendances

```bash
composer install
npm install
```

---

## 3. Configurer le fichier .env

```env
DB_DATABASE=smart_city
DB_USERNAME=root
DB_PASSWORD=
```

---

## 4. Générer la clé Laravel

```bash
php artisan key:generate
```

---

## 5. Lancer les migrations

```bash
php artisan migrate
```

---

## 6. Installer Filament

```bash
php artisan filament:install
```

---

## 7. Lancer le projet

```bash
php artisan serve
npm run dev
```

---

# Sécurité

Le système intègre :

* authentification sécurisée
* gestion des rôles
* permissions utilisateurs
* validation des formulaires
* protection CSRF
* contrôle des accès

---

# UX/UI

Le projet utilise une interface moderne avec :

* dashboards dynamiques
* dark mode
* cartes interactives
* responsive mobile
* tableaux filtrables
* statistiques temps réel
* notifications live

---

# Évolutions Futures

## Fonctionnalités futures

* IoT & capteurs intelligents
* IA prédictive
* détection automatique d’incidents
* application mobile Flutter
* notifications push mobiles
* machine learning
* chatbot assistance citoyen
* maintenance prédictive véhicules

---

# Avantages du Projet

* amélioration de la gestion urbaine
* meilleure réactivité des services
* optimisation des ressources
* suivi temps réel
* centralisation des données
* amélioration de la communication citoyenne

---

# Auteur

elmor

Technologies principales : Laravel 12, Filament, Livewire, MySQL et Google Maps API.

---

# Licence

Projet académique et éducatif.
