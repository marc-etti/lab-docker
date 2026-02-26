# Laboratorio Docker 00
In questo laboratorio eseguiremo un container Docker utilizzando un'immagine predefinita di Ubuntu, senza creare un Dockerfile personalizzato. Questo ci permetterà di familiarizzare con i comandi base di Docker per eseguire e gestire i container.

1. Eseguire un container basato sull'immagine ufficiale di Ubuntu:
    ```bash
    docker run -it --name my-ubuntu-container ubuntu:latest 
    ```
    Dove `-it` permette di interagire con il terminale del container, `--name my-ubuntu-container` assegna un nome al container, e `ubuntu:latest` specifica l'immagine da utilizzare.

2. Una volta avviato il container e entrati nel terminale, eseguire il comando `ll` per vedere i file presenti nella directory root del container.

3. Provare ad aggiornare i pacchetti all'interno del container eseguendo:
    ```bash
    apt update && apt upgrade -y
    ```

4. Per uscire dal terminale del container, digitare `exit`.

5. Per fermare il container, eseguire:
    ```bash
    docker stop my-ubuntu-container
    ```

6. Rilanciare il container in modalità detached (in background):
    ```bash
    docker start my-ubuntu-container
    ```

7. Per vedere i container in esecuzione, eseguire:
    ```bash
    docker ps
    ```

8. Per entrare nel terminale del container in esecuzione, eseguire:
    ```bash
    docker exec -it my-ubuntu-container bash
    ```
    dove `docker exec -it` permette di eseguire un comando interattivo all'interno del container, `my-ubuntu-container` è il nome del container, e `bash` è il comando per aprire una shell bash.

9. Per uscire dal terminale del container, digitare `exit`.

10. Per fermare il container, eseguire:
    ```bash
    docker stop my-ubuntu-container
    ```
11. Per rimuovere il container, eseguire:
    ```bash
    docker rm my-ubuntu-container
    ```

12. Rilanciare il container esponendo una porta (ad esempio la porta 80) per poter accedere a servizi che potrebbero essere in esecuzione all'interno del container:
    ```bash
    docker run -it -p 8080:80 --name my-ubuntu-container ubuntu:latest 
    ```
    Dove `-it` permette di interagire con il terminale del container, `-p 8080:80` mappa la porta 80 del container alla porta 8080 del host, e `--name my-ubuntu-container` assegna un nome al container.

13. Lasciare il terminale del container aperto e aprire un nuovo terminale. Per vedere i container in esecuzione, eseguire:
    ```bash
    docker ps
    ```
    Notare che il container è in esecuzione e che la porta 80 del container è mappata alla porta 8080 del host.
    ```
    CONTAINER ID   IMAGE           COMMAND       CREATED              STATUS              PORTS                                     NAMES
    7dfe65fe7141   ubuntu:latest   "/bin/bash"   About a minute ago   Up About a minute   0.0.0.0:8080->80/tcp, [::]:8080->80/tcp   my-ubuntu-container
    ```

14. Per fermare il container, eseguire:
    ```bash
    docker stop my-ubuntu-container
    ```

15. Per rimuovere il container, eseguire:
    ```bash
    docker rm my-ubuntu-container
    ```
    
16. Per rimuovere l'immagine, eseguire:
    ```bash
    docker rmi ubuntu:latest
    ```

### Torna all' [Indice dei laboratori](../README.md)
