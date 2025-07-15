# Spécification Fonctionnelle – Suivi Scolaire

## Objectif
Permettre aux administrateurs de gérer efficacement les élèves, classes, notes et messages au sein d’un établissement scolaire.

## Utilisateurs
- Administrateurs (accès complet)
- Enseignants (accès restreint, gestion des notes et messages)

## Modules principaux
1. **Gestion des élèves**
   - Ajout, modification, suppression, consultation
   - Import/export CSV
   - Profil détaillé (infos, parcours, santé)
2. **Gestion des classes**
   - Création, édition, suppression
   - Affectation des élèves
   - Statistiques par classe
3. **Gestion des notes**
   - Ajout, modification, suppression
   - Statistiques par élève et par classe
   - Export des notes
4. **Messagerie interne**
   - Envoi/réception de messages entre admins/enseignants
   - Pièces jointes, favoris, archivage
5. **Tableau de bord**
   - Statistiques globales (élèves, classes, messages)
   - Alertes (santé, absences, etc.)

## Contraintes
- Interface responsive et accessible (français/arabe)
- Authentification sécurisée
- Respect des bonnes pratiques Laravel

## Import/Export
- Modèle CSV fourni pour l’import d’élèves
- Export des données possible (élèves, notes)

## Sécurité
- Accès restreint par rôles
- Données sensibles protégées

---
Pour plus de détails, voir le README et la documentation technique. 