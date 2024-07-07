# PetShop API

This repository contains the solution for the PetShop API task given by Buckhill.

### Running the project.
Before running the project, make sure to have the following requirements met on your machine:

```shell
openssl >= 3.0.2
php >= 8.3
composer >= 2.7
```

To run the project, follow these simple steps:

1. Install composer dependencies: This is to ensure all dependencies are available locally
```shell
composer install
```

2. Copy `.env.example` file to `.env`.


3. Generate a pair of encryption keys: The application uses asymmetric encryption keys to generate JWT. make sure to generate these keys and place them in the correct folder.
```shell
# generate a private key:
openssl genpkey -algorithm RSA -out storage/keys/private_key.pem -pkeyopt rsa_keygen_bits:2048

# generate a public key for the previously generated private key
openssl genpkey -algorithm RSA -out keys/private_key.pem -pkeyopt rsa_keygen_bits:2048 
```

4. Set-up encryption keys: Make sure to move your keys into a secure folder and then, add the absolute paths to those keys in the .env file. specifically, using the `APP_PUBLIC_KEY` and `APP_PRIVATE_KEY` values.

5. Generate an app key: This is to be used with Laravel Encrypter class. You can definitely use the previously generated keys but to keep things simple, we use them only for generating JSON Web Tokens. For now, we'll use symmetric encryption for the other encryption concerns:
```shell
php artisan key:generate
```
6. Migrate the database:

```shell
php artisan migrate:fresh --seed
```
7. Last, start the server and navigate to the API docs:
```shell
php artisan serve 
```
Open up this link in the browser: [API docs](http://localhost:8000/api/documentation)

### Running the test suite:
This application uses Pest as its testing framework.
To run the tests, make sure you copy the content of the `.env.testing.example` into the `.env.testing` file, update the `.env.testing` file by adding the necessary values like the encryption keys, and then run the following command:

```shell
./vendor/bin/pest  # You can use --filter to select which tests to run...
```
