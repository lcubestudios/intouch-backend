## 🎯 Goal

Create a basic messaging API that enables code to send short messages through an SMS API platform.

## 📜 Main Use Case

Once you've set up a functional SMS API, it will help you:
-  Send and receive messages in different forms
-  Review the status of all your messages in real time

## 🦄 Features

-   Simple, reliable messaging
-   End-to-end message encryption

## 🧰 Prerequisites

#### Source Code
- Clone [repository](https://github.com/lcubestudios/messagingapp-api) (run the following command inside the terminal)

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

#### Auth
> Login

##### Endpoint: 
`{{server}}/{{reponame}}/auth.php?purpose=login`

##### Query Parameters: 
- Body JSON

```json
{
    "phone_number": 1234567890,
    "password": "Helloworld!"
}
```
##### Example:

**POST** http://serverip/messagingapp-api/auth.php?purporse=login
**response**

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

#### Auth 
> Register

##### Endpoint: 
`{{server}}/{{reponame}}/auth.php?purpose=reg`

##### Query Parameters: 
- Body JSON

```json
{
    "first_name": "John",
    "last_name": "Doe",
    "phone_number": 1234567890,
    "password": "Helloworld!"
}
```

##### Example:

**POST** http://serverip/messagingapp-api/auth.php?purporse=reg
**response**

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

#### Contact
>Load Contacts

##### Endpoint: 
`{{server}}/{{reponame}}/contacts.php`

##### Query Parameters: 
- Authorization Bearer Token - `Token: <token>`

##### Example:

**GET** http://serverip/messagingapp-api/contacts.php
**response**

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

#### Contact 
> Add Contact

##### Endpoint: 
`{{server}}/{{reponame}}/contacts.php`

##### Query Parameters: 
- Authorization Bearer Token - `Token: <token>`
- Body JSON
```json
{
    "phone_number": 1234567890
}
```
##### Example:

**POST** http://serverip/messagingapp-api/contacts.php
**response**

```json
{
    "status_code": 200,
    "message": "Contact added"
}
```

#### Contact 
> Delete Contact

##### Endpoint: 
`{{server}}/{{reponame}}/contacts.php`

##### Query Parameters: 
- Authorization Bearer Token - `Token: <token>`
- Body JSON
```json
{
    "phone_number": 1234567890
}
```
##### Example:

**DELETE** http://serverip/messagingapp-api/contacts.php
**response**

```json
{
    "status_code": 200,
    "message": "Contact has been deleted!"
}

```
#### Profile 
> Update Profile

##### Endpoint: 
`{{server}}/{{reponame}}/profile.php`

##### Query Parameters: 
- Authorization Bearer Token - `Token: <token>`
- Body JSON
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "old_password": "Helloworld!",
    "new_password": "Helloworld!%"
}
```
##### Example:

**PUT** http://serverip/messagingapp-api/profile.php
**response**

```json
{
    "status_code": 200,
    "message": "Profile updated!",
    "profile": {
        "first_name": "John",
        "last_name": "Doe",
        "phone_number": "1234567890",
        "token": "Your token"
    }
}
```
#### Messages
> Load Messages

##### Endpoint: 
`{{server}}/{{reponame}}/messages.php`

##### Query Parameters: 
- Authorization Bearer Token - `Token: <token>`
- Query Params - `?phone_number=1234567890`


##### Example:
**GET** http://serverip/messagingapp-api/messages.php?phone_number=1234567890
**response**

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
#### Messages
> Send Message

##### Endpoint: 
`{{server}}/{{reponame}}/messages.php`

##### Query Parameters: 
- Authorization Bearer Token - `Token: <token>`
- Body JSON
```json
{
    "phone_number": 1234567890,
    "body_text": "Hello John, How your doing?"
}
```
##### Example:

**POST** http://serverip/messagingapp-api/messages.php?phone_number=1234567890
**response**

```json
{
    "status_code": 200,
    "message": "message sent"
}
```
#### Messages
> Delete Messages

##### Endpoint: 
`{{server}}/{{reponame}}/messages.php`

##### Query Parameters: 
- Authorization Bearer Token - `Token: <token>`
- Body JSON
```json
{
    "phone_number": 1234567890
}
```
##### Example:

**DELETE** http://serverip/messagingapp-api/messages.php
**response**

```json
{
    "status_code": 200,
    "message": "All messages deleted"
}
```
---

## 👋 Meet the Authors

### Luis Muñoz

- [LinkedIn](https://www.linkedin.com/in/lmunoz0806/)
- [Github](https://github.com/lmunoz0806)

### Dennys Cedeño

- [LinkedIn](https://www.linkedin.com/in/dcedenor/)
- [Github](https://github.com/dennys9415)

## 📣 Connect with LCube Studios
- 🌎 [Website](https://Lcubestudios.io)
- ✉️ [Contact Us]("mailto:Contact@lcubestudios.io")
- 📅 [Let's Meet](https://calendly.com/lcubestudios/30min)
#### Follow Us
- [LinkedIn](https://www.linkedin.com/company/lcubestudios/)
- [Instagram](https://www.instagram.com/lcubestudios)
- [Facebook](https://www.facebook.com/lcubestudiosnyc/)
- [Twitter](https://www.twitter.com/lcubestudios/)
- [Discord](https://discord.com/invite/6Ud9x23zaK)
- [Github](https://github.com/lcubestudios)

## 💡 Let's make your FrameWork