# Laboratorio Docker 04
In questo laboratorio, riprenderemo RabbitMQ, un sistema di messaggistica open source che implementa il protocollo AMQP (Advanced Message Queuing Protocol).

1. Creazione del file `docker-compose.yml`:
    ```yaml
    services:
    rabbitmq:
        image: rabbitmq:3-management
        ports:
        - "5672:5672"  # Porta per AMQP
        - "15672:15672"  # Porta per l'interfaccia di gestione
        environment:
        - RABBITMQ_DEFAULT_USER={{RABBITMQ_DEFAULT_USER:-guest}}
        - RABBITMQ_DEFAULT_PASS={{RABBITMQ_DEFAULT_PASS:-guest}}
        volumes:
        - rabbitmq_data:/var/lib/rabbitmq
    volumes:
    rabbitmq_data:
    ```
    dove:
    - `image: rabbitmq:3-management` specifica l'immagine ufficiale di RabbitMQ con il plugin di gestione abilitato.
    - `ports` mappa le porte del container a quelle del host, permettendo l'accesso al servizio.
    - `volumes` definisce un volume per la persistenza dei dati di RabbitMQ, assicurando che i messaggi e le configurazioni non vadano persi quando il container viene riavviato o eliminato.
    - `environment` definisce le variabili d'ambiente per configurare l'utente e la password predefiniti di RabbitMQ, con valori di default `guest` se non specificati.
    - `rabbitmq_data` è il nome del volume che viene creato e montato all'interno del container.

2. Creazione del file `.env` (opzionale):
    ```env
    RABBITMQ_DEFAULT_USER=admin
    RABBITMQ_DEFAULT_PASS=adminpassword
    ```
    Questo file definisce le variabili d'ambiente per configurare l'utente e la password predefiniti di RabbitMQ. Se non viene creato, RabbitMQ utilizzerà i valori di default `guest` per entrambi.

3. Avvio del container:
    ```bash
    docker-compose up -d
    ```

4. Accesso all'interfaccia di gestione:
   - Aprire un browser e navigare su `http://localhost:15672`.
   - Le credenziali predefinite sono:
     - Username: `guest`
     - Password: `guest`

5. Adesso è possibile rifare il Laboratorio su RabbitMQ, reperibile al repository: https://github.com/RossiGaia/lab-AMQP

6. Per fermare e rimuovere il container:
    ```bash
    docker-compose down
    ```
7. Per rimuovere il volume dei dati (opzionale):
    ```bash
    docker volume rm lab-docker_rabbitmq_data
    ```
    Nota: Sostituire `lab-docker` con il nome del progetto Docker Compose se è diverso.

### Torna all' [Indice dei laboratori](../README.md)