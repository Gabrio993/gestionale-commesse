# Progetto Base CodeIgniter

Questo repository rappresenta il **progetto base ufficiale** dell’azienda per lo sviluppo di applicazioni in **CodeIgniter 3**.

L’obiettivo è fornire una struttura comune, convenzioni condivise e strumenti già pronti, in modo da garantire **coerenza, qualità e velocità** nello sviluppo di nuovi progetti.

---

## Requisiti
- PHP compatibile con CodeIgniter 3
- Composer (se previsto dal progetto)
- XAMPP

---

## Setup iniziale
1. Clona la repository
2. Configura il database in `application/config/database.php`
3. Configura il base URL `application/config/config.php`
4. Verifica il funzionamento 

---

## ⚠️ Convenzioni fondamentali (IMPORTANTISSIMO)

### MY_Controller e MY_Model
All’interno della cartella:

```
application/core/
```

sono stati creati:
- `MY_Controller.php`
- `MY_Model.php`

Queste classi **estendono le classi core di CodeIgniter** e contengono logica e funzionalità comuni a tutto il progetto.

👉 **REGOLA FONDAMENTALE**:

> ❌ NON estendere mai direttamente `CI_Controller` o `CI_Model`
>
> ✅ Estendere **SEMPRE** `MY_Controller` o `MY_Model`

### Esempi corretti

**Controller**
```php
class User extends MY_Controller
{
    public function index()
    {
    }
}
```

**Model**
```php
class User_model extends MY_Model
{
}
```

Questo garantisce:
- ereditarietà coerente
- funzioni condivise disponibili ovunque
- facilità di manutenzione e refactoring

---

## Supporto IDE (Intelephense / Autocompletamento)

È presente la cartella:

```
subs/ci.php
```

Questo file è uno **stub** delle classi core di CodeIgniter.

### A cosa serve?
- ✅ Migliora l’autocompletamento
- ✅ Elimina falsi errori dell’IDE
- ✅ Supporta Intelephense e strumenti simili

⚠️ **Nota importante**:
- Il file **NON viene incluso a runtime**
- Serve **solo per l’IDE**
- Non deve essere modificato se non per esigenze di sviluppo

---

## Estensioni IDE consigliate (VS Code)
Per lavorare correttamente sul progetto, si consiglia l’uso delle seguenti estensioni:

- **PHP Intelephense**  
  Autocompletamento, type inference, supporto avanzato PHP

- **PHP IntelliSense for CodeIgniter**  
  Supporto specifico per framework CodeIgniter

- **PHP CS Fixer**  
  Formattazione automatica del codice secondo standard condivisi

---

## Best practice
- Seguire le convenzioni di naming del progetto
- Non modificare le classi core senza confronto con il team
- Evitare logica duplicata: se è comune, va nel `MY_Controller` o `MY_Model`
- Scrivere codice leggibile e manutenibile

---

## Note finali
Questo progetto è pensato per essere usato come **template** per nuovi lavori.

In caso di dubbi, miglioramenti o proposte:
👉 confrontarsi con il team prima di applicare modifiche strutturali.

Buon lavoro 🚀

