# Laboratorio Docker 03
In questo laboratorio, vedremo come gestire più container utilizzando Docker Compose, uno strumento che permette di definire e gestire applicazioni multi-container in modo semplice e dichiarativo.

1. Creare un file chiamato `docker-compose.yml` con il seguente contenuto:
    ```yaml
    services:
    web:
        build: resources/app/
        ports:
        - 8000:8000
        environment:
        - DB_HOST=mariadb
        - DB_USERNAME=${DB_USERNAME}
        - DB_PASSWORD=${DB_PASSWORD}
        - DB_NAME=${DB_NAME}
        depends_on:
        - mariadb
    
    mariadb:
        image: mariadb
        environment:
        - MARIADB_ROOT_PASSWORD=${MARIADB_ROOT_PASSWORD}
        - DB_USERNAME=${DB_USERNAME}
        - DB_PASSWORD=${DB_PASSWORD}
        - DB_NAME=${DB_NAME}

        volumes:
        - ./resources/mariadb/init-db.sql.template:/tmp/init-db.sql.template
        - ./resources/mariadb/subst-vars.sh:/docker-entrypoint-initdb.d/00-subst-vars.sh
    ```
    In questo file, definiamo due servizi (`services`):

    - `web`, che rappresenta l'applicazione PHP
        - `build: resources/app/` indica che l'immagine per questo servizio deve essere costruita utilizzando il Dockerfile presente nella cartella `resources/app/`.
        - `ports` mappa la porta 8000 del container alla porta 8000 del host, permettendo di accedere all'applicazione tramite `http://localhost:8000`.
        - `environment` definisce le variabili d'ambiente necessarie per la connessione al database, che saranno lette dal file `.env`.
        - `depends_on` specifica che il servizio `web` dipende da `mariadb`, quindi Docker Compose si assicurerà che `mariadb` sia avviato prima di `web`.

    - `mariadb`, che rappresenta il database MariaDB. 
        - `image: mariadb` indica che questo servizio utilizza l'immagine ufficiale di MariaDB.
        - `environment` definisce le variabili d'ambiente necessarie per configurare il database, inclusa la password di root e le credenziali per l'utente del database.
        - `volumes` monta due file dal host al container:
            - `init-db.sql.template` è uno script SQL che verrà eseguito all'avvio del container per creare il database e l'utente.
            - `subst-vars.sh` è uno script che sostituisce le variabili d'ambiente nel file SQL prima di eseguirlo, permettendo di utilizzare le stesse variabili d'ambiente definite nel file `.env`.

2. Creare un file `.env` nella stessa directory del `docker-compose.yml` con le seguenti variabili d'ambiente:
    ```env
    DB_NAME=mydb
    DB_USERNAME=dbuser
    DB_PASSWORD=password
    MARIADB_ROOT_PASSWORD=rootpassword
    ```

3. Avviare i servizi definiti nel `docker-compose.yml` eseguendo il comando:
    ```bash
    docker compose up -d
    ```
    Questo comando costruirà l'immagine per il servizio `web` (se non è già stata costruita) e avvierà entrambi i servizi (`web` e `mariadb`) in modalità detached.

4. Per verificare che i servizi siano in esecuzione, eseguire:
    ```bash
    docker compose ps
    ```
    Dovresti vedere entrambi i servizi `web` e `mariadb` elencati come "Up".

5. Per accedere all'applicazione, aprire un browser e navigare verso `http://localhost:8000`. Dovresti vedere l'applicazione PHP che si connette al database MariaDB.

6. Per fermare i servizi, eseguire:
    ```bash
    docker compose down
    ```
    Questo comando fermerà e rimuoverà i container creati da Docker Compose, ma manterrà le immagini e i volumi.

7. Per rimuovere i volumi creati da Docker Compose, eseguire:
    ```bash
    docker compose down -v
    ```
    Questo comando fermerà i servizi e rimuoverà anche i volumi associati, eliminando i dati persistenti. Usare con cautela se si desidera mantenere i dati.

8. Per visualizzare i log dei servizi, eseguire:
    ```bash
    docker compose logs -f
    ```
    Questo comando mostrerà i log in tempo reale di tutti i servizi definiti nel `docker-compose.yml`. Puoi specificare un servizio specifico per vedere solo i suoi log, ad esempio:
    ```bash
    docker compose logs -f web
    ```
    Questo mostrerà solo i log del servizio `web`.

### Torna all' [Indice dei laboratori](../README.md)