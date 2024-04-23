# Shopify Demo App

### Requirement
- PHP >= 8.2
- Laravel = 10.48
- node >= 18

### ngrok

Download from https://ngrok.com/  and setup to run your project on local

## Create app in shopify https://partners.shopify.com/  
Copy Client ID And Client secret and paste in env file 
in configruation tab configure 
- app url from ngrok (example url https://immensely.com/)
- Allowed redirection URL + 'authenticate' (example url https://immensely.com/authenticate)


### Setup project
- Laravel setup
```sh
    > cp .env.example .env (Set the Database credentials)
    > composer install
    > php artisan key:generate
    > php artisan migrate
    > php artisan queue:work
```

- Set client and secret key of shopify in `config/shopify-app.php` or `.env`
- Compile VueJS assets
```sh
    > npm install
    > npm run dev
```
- Up the Laravel project
```sh
    php artisan serve
```

- Start the ngrok tunnel 
```sh
    ngrok http 8000
```

- Set ngrok https url in `.env` file


