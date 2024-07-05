# client_queue

You can use this API application to store your clients and form a queue for them.

### How to run (docker required):

1. Copy the repository from GitHub
```
git clone
```
2. Install required dependencies
```
composer install
```
3. Create a local environment file
```
cp .env.exampe .env
```

4. Generate a unique application key
```
php artisan key:generate
```
5. Set the data for connecting to the db in .env file
####
6. Run docker containers
```
./vendor/bin/sail up -d
```

### API methods:

1. POST /api/client - Create a client and add to the queue OR add existing client to the queue
####
    Parameters:
+ id (required) - Existing client ID

   OR
+ name and surname (required) - New client data
2. DELETE /api/client - Delete the client by id from the db and the queue
####
    Parameters:
+ id (required) - Existing client ID
3. DELETE api/client/queue - Delete the client by id from the queue
####
    Parameters:
+ id (required) - Existing client ID
4. GET api/clients - Get a list of all clients
5. GET api/client/queue - Get the client's data along with his position in the queue
####
    Parameters:
+ id (required) - Existing client ID
6. GET api/client/queue/current - Get the data of the first client in the queue
7. GET api/client/queue/process - Advance the queue after work with current client is completed

