# Laboratorio Docker 02
Scrittura di un Dockerfile per servire un'applicazione PHP con Apache. Verrà utilizzata l'immagine ufficiale di PHP con Apache già configurato, e si copierà un file PHP di esempio nella document root di Apache.

## Parte 1: Creazione del Dockerfile e costruzione dell'immagine

1. Creare un file chiamato `Dockerfile` con il seguente contenuto:
    ```Dockerfile
    # Usa l'immagine base di Ubuntu
    # Immagine ufficiale PHP con Apache già configurato
    FROM php:8.3-apache

    # Copia la tua app nella document root di Apache
    # (Apache servirà automaticamente i file da /var/www/html)
    COPY ./resources/todo-app.php /var/www/html/todo-app.php

    # Crea la cartella data per SQLite (se non esiste già) e imposta i permessi
    RUN mkdir -p /var/www/html/data && chown www-data:www-data /var/www/html/data

    # (Opzionale ma comodo) Imposta todo-app.php come pagina di default
    # così aprendo "/" vedi direttamente l'app.
    RUN mv /var/www/html/todo-app.php /var/www/html/index.php

    # Espone la porta 80 (Apache ascolta già su 80)
    EXPOSE 80
    ```

2. Costruire l'immagine Docker:
    ```bash
    docker build -t todo-app-image .
    ```
    Dove `-t todo-app-image` assegna un nome all'immagine e `.` indica che il Dockerfile si trova nella directory corrente.

3. Eseguire un container basato sull'immagine appena creata:
    ```bash
    docker run -d -p 8080:80 --name todo-app-container todo-app-image
    ```
    Dove `-d` esegue il container in modalità detached, `-p 8080:80` mappa la porta 80 del container alla porta 8080 del host, e `--name todo-app-container` assegna un nome al container.

4. Accedere all'applicazione aprendo un browser e navigando verso `http://localhost:8080`. Dovresti vedere il contenuto del file `todo-app.php`.

5. Per fermare il container, eseguire:
    ```bash
    docker stop todo-app-container
    ```

6. Per rimuovere il container, eseguire:
    ```bash
    docker rm todo-app-container
    ```



## Parte 2: Uso dei volumi per dati persistenti
L'applicazione `todo-app.php` salva i dati in SQLite dentro il file `data/todo.db`. Se si esegue il container senza volumi, ogni volta che lo si elimina o ricrea, si perderanno i dati salvati. Per evitare questo, è possibile utilizzare un volume Docker per mantenere i dati persistenti.

1. Creare un volume Docker:
    ```bash
    docker volume create todo-app-volume
    ```

2. Eseguire il container con il volume montato:
    ```bash
    docker run -d -p 8080:80 --name todo-app-container-with-volume \
        -v todo-app-volume:/var/www/html/data \
        todo-app-image
    ```
    dove `-v todo-app-volume:/var/www/html/data` monta il volume `todo-app-volume` al percorso `/var/www/html/data` all'interno del container, che è la cartella dove l'applicazione salva i dati.

3. Per verificare che i dati siano persistenti, puoi accedere al container e controllare che il file `todo.db` esista:
    ```bash
    docker exec -it todo-app-container-with-volume ls -la /var/www/html/data/
    ```
    A questo punto, anche se fermi e rimuovi il container, i dati rimarranno intatti nel volume. Puoi ricreare un nuovo container con lo stesso volume per continuare a utilizzare i dati salvati.

4. Per fermare il container, eseguire:
    ```bash
    docker stop todo-app-container-with-volume
    ```

5. Per rimuovere il container, eseguire:
    ```bash
    docker rm todo-app-container-with-volume
    ```

6. Per rimuovere l'immagine, eseguire:
    ```bash
    docker rmi todo-app-image
    ```


### Torna all' [Indice dei laboratori](../README.md)