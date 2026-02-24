# Laboratorio Docker 01
Scrittura di un Dockerfile per creare un'immagine personalizzata basata su Ubuntu, che installa Nginx e configura un semplice sito web `todo-app.php`.

1. Creare un file chiamato `Dockerfile` con il seguente contenuto:

    Usa l'immagine di base di Ubuntu:
    ```Dockerfile
    FROM ubuntu:latest
    ```
    Aggiorna i pacchetti e installa `Nginx` e `PHP-FPM`:
    ```Dockerfile
    RUN apt-get update && apt-get install -y nginx php-fpm
    ```
    Rimuovi il file di configurazione predefinito di Nginx:
    ```Dockerfile
    RUN rm /etc/nginx/sites-enabled/default
    ```
    Copia un file di configurazione personalizzato per Nginx:
    ```Dockerfile
    COPY resources/nginx.conf /etc/nginx/conf.d/default.conf
    ```
    Copia il file `todo-app.php` nella directory di Nginx:
    ```Dockerfile
    COPY resources/todo-app.php /var/www/html/index.php
    ```
    Espone la porta 80:
    ```Dockerfile
    EXPOSE 80
    ```
    Avvia php-fpm e Nginx quando il container viene eseguito:
    ```Dockerfile
    CMD service php-fpm start && nginx -g 'daemon off;'
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

7. Per rimuovere l'immagine, eseguire:
    ```bash
    docker rmi todo-app-image
    ```
    