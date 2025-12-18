# mylist-scoreUniBet

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](#)  
[![License](https://img.shields.io/github/license/LuCa1506s/mylist-scoreUniBet)](LICENSE)  
[![Version](https://img.shields.io/badge/version-0.1-blue)](#)

## 📌 Descrizione del progetto

**mylist-scoreUniBet** è un’applicazione web in PHP pensata per gestire e visualizzare liste e punteggi legati al mondo delle scommesse.  
Il progetto è organizzato in modo modulare, con cartelle dedicate a **CSS**, **database**, **logica PHP**, **asset pubblici** e **template**.

---

## 🚀 Perché è utile

- **Struttura modulare**: separazione chiara tra frontend e backend.  
- **Database pronto**: script SQL già inclusi in `/db` per creare lo schema.  
- **Template personalizzabili**: interfaccia facilmente adattabile.  
- **Scalabilità**: architettura predisposta per nuove funzionalità.  
- **Open source**: chiunque può contribuire e migliorare il progetto.

---

## ⚙️ Come iniziare

### Prerequisiti
- PHP ≥ 7.4  
- MySQL o MariaDB  
- Composer (opzionale)  
- Server locale (Apache, Nginx, XAMPP)

### Installazione

```bash
# Clona il repository
git clone https://github.com/LuCa1506s/mylist-scoreUniBet.git

# Vai nella cartella del progetto
cd mylist-scoreUniBet

# Importa lo schema del database
mysql -u <utente> -p < db/schema.sql
