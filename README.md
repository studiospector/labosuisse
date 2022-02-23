## Flusso 🚰

#### Prerequisiti

Come primo step dobbiamo creare un access token su [Gitlab](git.caffeina.co).
Per fare questo vai nel tuo profilo utente su Gitlab e dal menu a sinistra seleziona la tab **Access Token**.

Inserisci un nome per il token e **assicurati** di selezionare le checkbox:
* api
* read_registry

A questo punto puoi creare il token.

Successivamente lanciare il seguente comando:
`docker login registry.caffeina.co -u <gitlab-username> -p <access-token>`
dove il gitlab-username sarà la tua mail di caffeina e la password sarà l'access token creato precedentemente.

> **Tip**: Assicurati che nella cartella ~/.docker il file config.json abbia la voce auths come segue auths: {}

-

Assicurati di avere tirato su il reverse proxy globale che serve per risolvere i domini in ambiente locale.

Tramite `nador-cli` inizializzare il load balancer

```sh
nador setup
```

Per poi tirarlo su semplicemente con il comando

```sh
nador loadbalancer
```


#### Sviluppo

Una volta creato il progetto con [nador-cli](https://git.caffeina.co/devops/nador-cli) puoi avviare l'ambiente di sviluppo con `./cmd/dev.sh`

Questo avvierà l'intero stack di sviluppo definito dai servizi scelti durante la creazione del progetto.

> **Tip**: per lanciare comandi `npm` puoi usare il comando `/cmd/npm.sh <service> <args>`.

##### Local problems

Se hai problemi con `max_allowed_packet` segui i passaggi sotto:
- `docker exec -it labo-website-2021_storage_mysql_1 sh`
- `apt-get update`
- `apt-get install nano`
- `cd etc`
- `cd mysql`
- `nano my.cnf`
- aggiungi al file la riga `max_allowed_packet=500M` con il valore che serve
- salva il file
- riavvia il container

#### Deploy

Inserire la variabile **n2_env** per la CI in Gitlab copiando al suo interno tutte le variabili presenti nel tuo **.env** in locale.

Per mettere un progetto online ti basterà inviare un commit.
Una pipeline di CI partirà automaticamente.


## Branches

- MASTER: deploys on *aws production*
- PREPROD: deploys on *aws preproduction*
- DEVELOP: deploys on *n3 stage*

Branch produzione: master
Hotfix partono da: master
Feature partono da: preprod
Release partono da: preprod

Develop: branch che contiene feature non complete e altre cose WIP. Questo branch è "sporco", **NON** deve essere mergiato dentro altri branch e può essere eliminato in ogni momento perché il codice che contiene esiste anche in altri branch (features/master/preprod).

#### Branch Protections:
Protezione branch abilitata per:
- master
- preprod

### Release Flow
1. Crea nuovo branch `release/` partendo da `preprod`
2. Fai fix dell'ultimo minuto (eg bump del numero di versione)
3. Crea tag di versione
4. Chiudi la release facendo merge in `preprod` e `master`
5. Cancella branch di release
6. _bonus_: usa `git flow release start` e  `git flow release finish` per ottimizzare il processo dei punti precedenti.
#### Posso fare il merge della mia feature dentro `preprod`?
Sì, ma solo se è finita ed è stata testata (dai tester e/o PM)

#### Posso fare il merge di `preprod` nella mia feature?
Sì.

#### La mia feature è terminata e deve essere testata:
Fai il merge del branch dentro a `develop` e sposta il task in `Ready for Test` nella kanban.

#### Posso fare il merge di `develop` nel mio branch?
NO.

#### Posso fare il merge di `develop` in `master`?
ASSOLUTAMENTE NO.

#### Posso fare il merge di `develop` in `preprod`?
NO.
