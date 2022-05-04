# Messaging App

[![LCubeStudios](https://badgen.now.sh/badge/Developed%20by/LCube%20Studios/?color=FFCB05)](https://lcubestudios.io)

![Banner](/assets/banner.png?raw=true "Banner")

Messaging App is a microframework for messaging and managing private communications between users. It is an open source project that is built with Github and Docker, making it accessible and easy to install on private servers. The application is self hosted, ensuring that in-house data is stored in a private database.

It is a white-label product that is flexible to color and logo customizations, offering organizations the ability to design personalized aesthetics.

## Important Links

🕹️ [Demo](https://demo.lcubestudios.io/messagingapp-frontend)

📝 [Case Study](https://lcubestudios.io/work/messaging-app)

📒 [Documentation](/README.md)

## Source Code

⚙️ [Frontend Repository](https://github.com/lcubestudios/messagingapp-frontend)

🗄 ️[Backend Repository](https://github.com/lcubestudios/messagingapp-api)

🐳 [Docker Repository](https://github.com/lcubestudios/messagingapp-docker)

## Technologies Used

- Package management: [Yarn](https://yarnpkg.com/)
- UI framework: [VueJS](https://vuejs.org/)
- Styling: [Tailwlind CSS](https://tailwindcss.com/)
- Formatting: [Prettier](https://prettier.io/) & [ESLint](https://eslint.org/)

## 🧰 Prerequisites

#### Source Code
Clone [repository](https://github.com/lcubestudios/messagingapp-api) (run the following command inside the terminal)

  ```sh
  git clone https://github.com/lcubestudios/messagingapp-api
  cd messagingapp-api
  ```
  > Keep this terminal active, this is where you will be required to run the commands stated below

#### Postman

- [Download](https://www.postman.com/downloads/) Postman
- Follow [How to Guide](https://learning.postman.com/docs/getting-started/importing-and-exporting-data/) to Import Postman [Collection](https://www.getpostman.com/collections/3692a0aa4daa3d16f40c)

## 📚 Additional Links

-   [Install Apache2 on Ubuntu 20.04 LTS](https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-ubuntu-20-04)
-   [Install PHP 8.1 on Ubuntu 20.04 LTS](https://cloudcone.com/docs/article/how-to-install-php-8-1-on-ubuntu-20-04-22-04/)
-   [Install Composer on Ubuntu 20.04 LTS](https://www.digitalocean.com/community/tutorials/how-to-install-composer-on-ubuntu-20-04-quickstart)
-   [Install PostreSQL on Ubuntu 20.04 LTS](https://www.digitalocean.com/community/tutorials/how-to-install-postgresql-on-ubuntu-20-04-quickstart)
-   [Install PHP PGSQL Module on Ubuntu 20.04 LTS]()


## OPTION 1 - Building from source
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
    ```
#### Step 5: Test endpoints
1. In **POSTMAN** Update `server` variable in the collection to your server address
2. Test CRUD commands in **POSTMAN** collection: `GET`, `PUT`, `POST`, `DELETE`

#### 🎉 DONE

---

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
        "phone_number": 1234567890
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
        "phone_number": 1234567890
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
            "phone_number": "1234567890",
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
