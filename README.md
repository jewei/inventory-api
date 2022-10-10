# Inventory API

For simple add/deduct inventory's quantity.

## Concept

1. A event listener is created called `AsyncUpdateInventoryQuantity` and it listens to the `UpdateInventoryQuantity` event.
2. To make it queueable, this listener implements `ShouldQueue` indicating to Laravel that the job should be pushed onto the queue to run asynchronously. 
3. This listener also implements `ShouldBeUnique` to ensure that only one instance of a specific job is on the queue at any point in time.
4. When the listener handle the job, it updates the quantity of the specific invetory with pessimistic locking `lockForUpdate`.

## Installation and running the demo App

1. Git clone the project and change the working directory to the project root.
2. Run the command `cp .env.example .env` to define environment variables.
3. Install dependencies with `composer install`.
3. Generate app key with `./vendor/bin/sail artisan key:generate`.
4. Make sure Docker is running. Then run `./vendor/bin/sail up -d` to start and run the container.
5. Run DB migration and seeding `./vendor/bin/sail artisan migrate:fresh --seed`.
6. Send a couple of API requests with `curl --request POST --url http://localhost/api/inventories/random`.
7. Run the worker manually `./vendor/bin/sail artisan queue:work`.

## Assumptions

1. API authentications and authorization are not handled in this demo app.
2. Rate limiting is following Laravel's default: max 60 attempts per minute by IP address.
3. If APIs were authenticated, `uniqueId` should be use and define the job unique key with sender's ID.
4. The API should return a unique request ID. With this ID the API sender can check the result of the sent API. (Out of scope for now)
