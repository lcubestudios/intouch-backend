pipeline {
    agent { label 'lcubes-demo-server' }

    options {
    buildDiscarder(logRotator(numToKeepStr: '5'))
    }

    environment {
        DOCKER_CRED = credentials('lcubestudio-docker')
    }
    stages {
        stage('Init') {
            steps{
                echo "loading the function file"                
            }        
        }
        stage('Build') {
            steps{
                echo "Building images"
                sh 'if [ ! -d /var/www/html/prod/intouch-backend/ ]; then mkdir -p /var/www/html/prod/intouch-backend/; fi'
                sh 'rsync -uqr --delete-during /var/www/jenkins/workspace/intouch-backend_prod/ /var/www/html/prod/intouch-backend/'
            }        
        }
        stage('Jenkins') {
            steps{
                echo 'running command for containers in the Server'
                echo 'Done'  
            }        
        }
    }
    post {
        always{
            echo 'Login Out of the Account'
        }
    }
}
