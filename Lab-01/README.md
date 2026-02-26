# Laboratorio Docker 01
In questo laboratorio creeremo un'immagine Docker personalizzata basata su Ubuntu, che installa Nginx e configura un semplice sito web `index.html`.

1. Creare un file chiamato `Dockerfile` con il seguente contenuto:
    ```Dockerfile
    # Usa Ubuntu come immagine base
    FROM ubuntu:latest

    # Aggiorna i pacchetti e installa Nginx
    RUN apt-get update && \
        apt-get install -y nginx

    # Copia il file resources/index.html nella directory web di Nginx
    COPY resources/index.html /var/www/html/index.html

    # Espone la porta 80
    EXPOSE 80

    # Avvia Nginx in foreground (necessario per Docker)
    CMD ["nginx", "-g", "daemon off;"]
    ```

2. Costruire l'immagine Docker:
    ```bash
    docker build -t my-nginx-image .
    ```
    Dove `-t my-nginx-image` assegna un nome all'immagine e `.` indica che il Dockerfile si trova nella directory corrente.

3. Eseguire un container basato sull'immagine appena creata:
    ```bash
    docker run -d -p 8080:80 --name my-nginx-container my-nginx-image
    ```
    Dove `-d` esegue il container in modalità detached, `-p 8080:80` mappa la porta 80 del container alla porta 8080 del host, e `--name my-nginx-container` assegna un nome al container.

4. Accedere al sito web aprendo un browser e navigando verso `http://localhost:8080`. Dovresti vedere il contenuto del file `index.html`.

5. Per fermare il container, eseguire:
    ```bash
    docker stop my-nginx-container
    ```

6. Per rimuovere il container, eseguire:
    ```bash
    docker rm my-nginx-container
    ```
7. Per rimuovere l'immagine, eseguire:
    ```bash
    docker rmi my-nginx-image
    ```

### Torna all' [Indice dei laboratori](../README.md)