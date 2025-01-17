# Messaging App

[![LCubeStudios](https://badgen.now.sh/badge/Developed%20by/LCube%20Studios/?color=FFCB05)](https://lcubestudios.io)

![Banner](/demo_assets/banner.png?raw=true "Banner")

Messaging App is a microframework for messaging and managing private communications between users. It is an open source project that is built with Github and Docker, making it accessible and easy to install on private servers. The application is self hosted, ensuring that in-house data is stored in a private database.

It is a white-label product that is flexible to color and logo customizations, offering organizations the ability to design personalized aesthetics.

## Important Links

🕹️ [Demo](https://demo.lcubestudios.io/messagingapp-frontend)

📒 [Documentation](/README.md)

## Installation Options

### 🐳 Option 1 : Containerized Docker
This option allows installation of frontend and backend environments as a containerized docker image with env configurations.

Links:
- [Docker Repository](https://github.com/lcubestudios/messagingapp-docker)

### ⚙️ Option 2 : Download from source code
This option allows you to download and install frontend and backend code independently

Links:
- [Frontend Repository](https://github.com/lcubestudios/messagingapp-frontend)
- ️[API Repository](https://github.com/lcubestudios/messagingapp-api)

# API Documentation

## Technologies Used

- Package management: [Composer](https://getcomposer.org/)
- Programming language: [PHP 8.1](https://www.php.net/downloads.php)

## 🧰 Prerequisites

#### Postman

- [Download](https://www.postman.com/downloads/) Postman
- Follow [How to Guide](https://learning.postman.com/docs/getting-started/importing-and-exporting-data/) to Import Postman [Collection](https://www.getpostman.com/collections/3692a0aa4daa3d16f40c)

## 📚 Additional Links

-   [Install Apache2 on Ubuntu 20.04 LTS](https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-ubuntu-20-04)
-   [Install PHP 8.1 on Ubuntu 20.04 LTS](https://cloudcone.com/docs/article/how-to-install-php-8-1-on-ubuntu-20-04-22-04/)
-   [Install Composer on Ubuntu 20.04 LTS](https://www.digitalocean.com/community/tutorials/how-to-install-composer-on-ubuntu-20-04-quickstart)
-   [Install PostreSQL on Ubuntu 20.04 LTS](https://www.digitalocean.com/community/tutorials/how-to-install-postgresql-on-ubuntu-20-04-quickstart)
-   [Install PHP PGSQL Module on Ubuntu 20.04 LTS]()

## Installation

Clone repository
```sh
git clone https://github.com/lcubestudios/messagingapp-api.git
```

Change to project directory
```sh
cd messagingapp-api
```

Checkout branch
```sh
git fetch && git checkout { branch name }
```

## Build
> Make sure you have completed the tasks mentioned in the [Prerequisites](#-prerequisites) section above before proceeding

#### Before you Start:

##### Git
Make sure you are on the **master** branch (run the following command inside the terminal)
```sh
git checkout master
```

#### Step 1: Install Server Requirements
- Apache2
- PHP 8.1
- Composer
- PHP PGSQL module (php8.1-pgsql) 

#### Step 2: Create API database user

Update 'YOURPASSWORD' to something more secure then run the following command inside _psql_ or _PHPpgAdmin_. 

```sql
CREATE USER api_user WITH PASSWORD 'YOURPASSWORD';
```

#### Step 3: Create demo database & import schema
```sql
CREATE DATABASE demo;
```
Import schema using _psql_ or _PHPpgAdmin_ using a user with privelleges. 
[Import SQL DUMP](https://chemicloud.com/kb/article/import-and-export-a-postgresql-database/#how-to-import-a-postgresql-database)

```sql
psql -U postgres demo < messaging_app_schema.pgsql
```
#### Step 4: Set up environment

1. Duplicate the `.env.sample` file and rename it to `.env`
2. Update `.env` variables

    ```
    ## DATABASE ##
    DB_HOST=“YOUR_DB_IP”
    DB_NAME=“YOUR_DB_NAME”
    DB_USER=“YOUR_DB_USERNAME”
    DB_PASSWORD=“YOUR_DB_PASSWORD”
    DB_PORT=YOUR_PORT

    # DB Tables - default tables names  ##
    USERS_TABLE='public.messaging_app_user'
    CONTACTS_TABLE='public.messaging_app_contacts'
    MESSAGES_TABLE='public.messaging_app_messages'

    ## DEFAULT DB Columns ##
    ## Messaging_app_user table default colummns names ##
    DB_ID_KEY='u_id'
    DB_USERNAME_KEY='username'
    DB_PASSWORD_KEY='password'
    DB_ACESSS_TOKEN_KEY='token'
    DB_USER_FIRST_NAME_KEY='first_name'
    DB_USER_LAST_NAME_KEY='last_name'

    ## Messaging_app_contacts table default colummns names ##
    DB_USER_ID_KEY='u_id'
    DB_CONTACT_ID_KEY='c_uid'

    ## Messaging_app_messages table default colummns names ##
    DB_SENDER_ID_KEY='sender_id'
    DB_RECIEVER_ID_KEY='reciever_id'
    DB_MESSAGE_BODY_KEY='body_text'
    DB_READ_STATUS_KEY='reciever_read'
    DB_MESSAGE_DATE_KEY='date'
    DB_MESSAGE_ID_KEY='message_id'
    DB_MESSAGE_TYPE_KEY='message_type'
    DB_FILE_DATA_KEY='raw_data'
    DB_FILE_EXTENSION_KEY='file_extension'
    ```
#### Step 5: Test endpoints
1. In **POSTMAN** Update `server` variable in the collection to your server address
2. Test CRUD commands in **POSTMAN** collection: `GET`, `PUT`, `POST`, `DELETE`

## How to use the solution

### User Login

**Endpoint:**
**POST/** `{{server}}/{{reponame}}/auth.php?purpose=login`

**Body:**

```json
{
    "username": "john_doe",
    "password": "Helloworld!"
}
```

**Response:**

```json
{
    "status_code": 200,
    "message": "Login sucessful!",
    "results": {
        "token": "Your token",
        "first_name": "Your name",
        "last_name": "Your last name",
        "username": 1234567890
    }
}
```

### User Registration

**Endpoint:**
**POST/** `{{server}}/{{reponame}}/auth.php?purpose=reg`

**Body:**

```json
{
    "first_name": "John",
    "last_name": "Doe",
    "username": "john_doe",
    "password": "Helloworld!"
}
```

**Response:**

```json
{
    "status_code": 200,
    "message": "User has been created",
    "results": {
        "token": "Your Token",
        "first_name": "Your name",
        "last_name": "Your last name",
        "username": 1234567890
    }
}
```

#### Load Contacts

**Endpoint:**
**GET/** `{{server}}/{{reponame}}/contacts.php`

**Headers:**

```json
{
  "Authorization Bearer Token": "Token: <token>"
}
```

**Response:**

```json
{
    "status_code": 200,
    "contacts": [
        {
            "username": "1234567890",
            "first_name": "John",
            "last_name": "Doe",
            "u_id": "2",
            "unread": "0"
        }
    ]
}
```

#### Add New Contact 

**Endpoint:**
**POST/** `{{server}}/{{reponame}}/contacts.php`

**Headers:**

```json
{
  "Authorization Bearer Token": "Token: <token>"
}
```

**Body:**

```json
{
    "username": "john_doe"
}
```

**Response:**

```json
{
    "status_code": 200,
    "message": "Contact added"
}
```

#### Delete contact 

**Endpoint:**
**DELETE/** `{{server}}/{{reponame}}/contacts.php`

**Headers:**

```json
{
  "Authorization Bearer Token": "Token: <token>"
}
```

**Body:**

```json
{
    "username": "john_doe"
}
```

**Response:**

```json
{
    "status_code": 200,
    "message": "Contact has been deleted!"
}

```
#### Update Profile 

**Endpoint:**
**PUT/** `{{server}}/{{reponame}}/profile.php`

**Headers:**

```json
{
  "Authorization Bearer Token": "Token: <token>"
}
```

**Body:**

```json
{
    "first_name": "John",
    "last_name": "Doe"
}
```

**Response:**

```json
{
    "status_code": 200,
    "message": "Profile updated!",
    "profile": {
        "first_name": "John",
        "last_name": "Doe",
        "username": "john_doe",
        "token": "Your token"
    }
}
```
#### Load Messages

**Endpoint:**
**GET/** `{{server}}/{{reponame}}/messages.php?username={ username }`

**Headers:**

```json
{
  "Authorization Bearer Token": "Token: <token>"
}
```

**Response:**

```json
{
    "status_code": 200,
    "messages": [
        {
            "sender_id": "1",
            "receiver_id": "2",
            "body_text": "Hello John, How your doing",
            "messages_read": "f",
            "date_message": "2022-04-18 00:31:11.757233+00",
            "message_id": "1",
            "is_sender": true
        },
        {
            "sender_id": "2",
            "receiver_id": "1",
            "body_text": "Hello Doe, How your doing",
            "messages_read": "t",
            "date_message": "2022-04-18 00:31:57.460954+00",
            "message_id": "2",
            "is_sender": false
        }
    ]
}
```
#### Send Message

**Endpoint:**
**POST/** `{{server}}/{{reponame}}/messages.php`

**Headers:**

```json
{
  "Authorization Bearer Token": "Token: <token>"
}
```

**Body:**

```json
{
    "username": "john_doe",
    "body_text": "Hello John, How your doing?"
}
```

**Response:**

```json
{
    "status_code": 200,
    "message": "message sent"
}
```
#### Delete Message History

**Endpoint:**
**DELETE/** `{{server}}/{{reponame}}/messages.php`

**Headers:**

```json
{
  "Authorization Bearer Token": "Token: <token>"
}
```

**Body:**

```json
{
    "username": "john_doe"
}
```

**Response**

```json
{
    "status_code": 200,
    "message": "All messages deleted"
}
```

## 🤝 Connect with LCube Studios

🌎 [Website](https://Lcubestudios.io)

✉️ [Contact Us](mailto:Contact@lcubestudios.io)

📅 [Let's Meet](https://calendly.com/lcubestudios/30min)

## 🤘 Follow Us
[LinkedIn](https://www.linkedin.com/company/lcubestudios/)

[Instagram](https://www.instagram.com/lcubestudios)

[Facebook](https://www.facebook.com/lcubestudiosnyc/)

[Twitter](https://www.twitter.com/lcubestudios/)

[Discord](https://discord.com/invite/6Ud9x23zaK)

[Github](https://github.com/lcubestudios)

## 💡 Let's make your FrameWork