<div align="center"><a name="readme-top"></a>

[![][image-banner]][website-link]

# Plux Market DB Connector PHP

Plux Market works for rAthena emulator. It is a web application that lets players search for items by name or ID to find sellers and prices. It provides statistics like average prices, recent sales, and a 30-day price trend graph, helping players make informed buying and selling decisions.

## What is the Plux Market DB Connector?

The Plux Market DB Connector is a serverless API built in Next.Js that connects to your rAthena MySQL database to fetch the data needed for the Plux Market web application. It helps as a proxy between the Web Application and the MySQL database.

</div>

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

## Getting started

1. (Recommended) Start by creating a MySQL user specifically for this connector with READ only permissions on the following tables. To do that, follow the commands below:

- Change `databaseName` to your database name.
- Change `strongPassword` to a strong password.

```
-- Drop the existing user
DROP USER 'pluxmarketconnector'@'%';

-- Step 1: Create the user
CREATE USER 'pluxmarketconnector'@'%' IDENTIFIED BY 'strongPassword';

-- Step 2: Grant permissions to the specified tables
GRANT SELECT ON `databaseName`.vendings TO 'pluxmarketconnector'@'%';
GRANT SELECT ON `databaseName`.vending_items TO 'pluxmarketconnector'@'%';
GRANT SELECT ON `databaseName`.cart_inventory TO 'pluxmarketconnector'@'%';
GRANT SELECT ON `databaseName`.char TO 'pluxmarketconnector'@'%';
GRANT SELECT ON `databaseName`.item_db TO 'pluxmarketconnector'@'%';

-- Step 3: Flush the privileges
FLUSH PRIVILEGES;

```

2. Do a composer install to install the dependencies.

```bash
composer install
```

3. Create a `.env` file in the root directory of the project with the following variables:

- MYSQL_HOST
- MYSQL_PORT
- MYSQL_DATABASE
- MYSQL_USER (the new user you just made in step 1)
- MYSQL_PASSWORD (the password you added to that user)
- API_SECRET_TOKEN (this is basically any secret value you would like to have. Think of this as a very strong password)

4. Follow the same steps you did to install FluxCP.

5. The api endpoint should be available at `https://[url]/api/executeQuery`.

- Share both the `API_SECRET_TOKEN` and the `URL` of this deployment with the Plux Market Web Application to authenticate the requests.

## Deploying with Docker

Pre-built image will be available at Docker Hub soon. For now you can build and deploy the image yourself.

1. You can build the Dockerimage by running the following command in the root directory of the project:

```bash
docker build -t plux-market-db-connector .
```

2. If you want to run the container, you can do so by running the following command:

```bash
docker run -p 8080:8080 -e MYSQL_HOST=your_host -e MYSQL_PORT=3306 -e MYSQL_DATABASE=your_database -e MYSQL_USER=your_user -e MYSQL_PASSWORD=your_password -e API_SECRET_TOKEN=your_secret_token plux-market-db-connector
```

## Plux Market Features

### General Features

- Listing 100 random items on the startpage
- Searching item by name or id. Name doesn't need to be complete.
- Dark/Light mode adapting to system preferences.
- Responsive design for mobile and desktop
- Fetching metadata of each item when it's clicked
- Show map where the seller is. Click on the location will save `/navi [coordinates]` on clipboard, so it can be pasted in game.
- Show different colors based on price.
- Show refine rate of item in the name
- Show slots of item in the name
- Show card(s) name attached to the item
- Show forged elemental status and name of item (Very Very)
- Sort the table by Price
- Add pagination on the table to show 10 items per page
- Support to share a searched page link (if people want to share the url to another friend)
- Items not being sold can still be searched and see their statistics
- It's way faster than Flux CP

### Statistics Features

- See item statistics (needs custom code on rAthena)
  - Amount of items sold
  - Average price of the item
  - Last timestamp when the item was sold
  - See a graph of the last 30 days of the price development of the item
- Show last item sold
- Show most popular item of the week and compare it to previous week
- Show most expensive item sold on the week
- Show the total amount of zeny spent in the server per week & all time.

<!-- LINK GROUP -->

[website-link]: https://plux.dev
[image-banner]: https://plux.dev/images/db-connector-image.png

## About the Project

- Build using [PHP Slim Framework](https://github.com/slimphp/Slim)
