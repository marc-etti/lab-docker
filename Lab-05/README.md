# Laboratorio Docker 05
In questo laboratorio, vedremo come utilizzare docker come ambiente di sviluppo per un progetto PHP, sfruttando i vantaggi dei volumi per mantenere il codice sorgente persistente e facilmente modificabile. 

1. Creazione il `Dockerfile` per l'immagine PHP con Apache. Questo file definirà l'ambiente di sviluppo, installando eventuali strumenti utili e configurando Apache in modo "dev-friendly".
    ```dockerfile
    FROM php:8.3-apache

    # Abilita mod_rewrite (opzionale ma utile)
    RUN a2enmod rewrite

    # Impostazioni Apache "dev-friendly"
    RUN { \
        echo "ServerName localhost"; \
    } > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

    # DocumentRoot di default: /var/www/html
    WORKDIR /var/www/html
    ```
    dove:
    - `FROM php:8.3-apache` utilizza l'immagine ufficiale di PHP con Apache preinstallato.
    - `a2enmod rewrite` abilita il modulo rewrite di Apache, utile per molti framework PHP.
    - La configurazione del `ServerName` evita warning di Apache all'avvio.
    - `WORKDIR` imposta la cartella di lavoro all'interno del container, dove risiederà il codice sorgente.

2. Creazione del `docker-compose.yml` per definire i servizi necessari:
    ```yaml
    services:
        web:
            build: .
            container_name: php-apache-dev
            ports:
            - "8080:80"
            volumes:
            - ./src:/var/www/html:rw
            environment:
            - TZ=Europe/Rome
    ```
    dove:
    - volumes: `./src:/var/www/html:rw` monta la cartella `src` del progetto sulla cartella di lavoro del container, permettendo di modificare i file localmente e vederli riflessi immediatamente nel container.

3. Creazione della cartella `src` e del file `index.php` al suo interno:
    ```php
    <?php
    declare(strict_types=1);

    $message = "Hello World da PHP dentro Docker!";
    $now = (new DateTimeImmutable("now"))->format("Y-m-d H:i:s");
    ?>
    <!doctype html>
    <html lang="it">
    <head>
        <meta charset="utf-8">
        <title>Hello World - Docker Dev</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <h1><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></h1>
        <p>Ora dal container: <strong><?= htmlspecialchars($now, ENT_QUOTES, 'UTF-8') ?></strong></p>
        <p>Prova a cambiare il testo di <code>$message</code> in questo file e ricarica.</p>
    </body>
    </html>
    ```

4. Avvio del container con `docker-compose up --build` e accesso a `http://localhost:8080` per vedere il risultato.

5. Modificando il file `index.php` nella cartella `src`, le modifiche saranno immediatamente visibili nel container. Basta salvare il file e ricaricare la pagina nel browser per vedere le modifiche.

6. Per fermare il container, utilizzare `docker-compose down`.

### Torna all' [Indice dei laboratori](../README.md)