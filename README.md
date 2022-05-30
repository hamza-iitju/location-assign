# Welcome to eCare Backend!

To run this project you simply need to have docker and docker-compose installed in your system. Any dependency of php, composer, MySql, phpMyAdmin is not mandatory

# Requirements
- Docker
- Docker-compose

## Executing commands
As we are using docker we don't want to run any npm, php or composer command with our local environment. Instead we will let docker to handle these. Simple shell scripts are added in to project to simplify running thoose commands.

#### **npm**
To run any of the npm commands we can use npm.sh file to execute those commands using help of docker image. We just need to replace npm with ./npm. For example to run npm install we can run

    ./npm.sh install
Also, we can run

    ./npm.sh -v
    ./npm.sh install <package-name>

### **Composer**
Similarly, for composer we can use composer.sh to run composer commands. For example

    ./composer.sh install

### **Artisan**
Also, we can use  artisan.sh to run artisan commands. For example

    ./artisan.sh make:Model TestModel

### Note:
If you face any permission denied error while executing these command, you need to give executable permission to that scripts.

    sudo chmod 744 npm.sh
    sudo chmod 744 composer.sh
    sudo chmod 744 artisan.sh

Or you can run

    sudo chmod 744 composer.sh npm.sh artisan.sh

## Running Project Locally
Make sure you have .env file ready properly in src folder. We must set a DB_USERNAME and DB_PASSWORD value to successfully run mysql server

    docker-compose up --build

To run docker in detached mode

    docker-compose up -d --build

Run migrations using artisan.sh

    ./artisan.sh migrate

Finally, the laravel server will be at

    localhost:8080

phpMyAdmin will be at

    localhost:8081
