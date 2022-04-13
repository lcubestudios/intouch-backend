pipeline{
    agent { label 'api-node' }
    //tools { nodejs 'node-14.18.3' }
    
    environment {
        REPO_NAME = 'messagingapp-api' //Mandatory
        
        //Do not modify
        APACHE_DIR = '/var/www/html'
        SNYK_ID = 'lcube-snyk-token'
        JK_WORKSPACE = '/var/www/jenkins/workspace'
    }
   stages{
        stage("build") {
            steps {
                echo "building the application on ${NODE_NAME}."
                slackSend color: "warning", message: "Starting build process for ${REPO_NAME} from ${BRANCH_NAME} branch..."
                sh 'if [ ! -d ${APACHE_DIR}/${BRANCH_NAME}/${REPO_NAME}/ ]; then mkdir -p ${APACHE_DIR}/${BRANCH_NAME}/${REPO_NAME}/; fi'
                sh 'rsync -Puqr --delete-during ${JK_WORKSPACE}/${REPO_NAME}_${BRANCH_NAME}/ ${APACHE_DIR}/${BRANCH_NAME}/${REPO_NAME}/'
                // sh 'find ${APACHE_DIR}/${BRANCH_NAME}/${REPO_NAME}/ -name "*.py" -exec chmod 665 {} \\;'
                // sh 'cd ${APACHE_DIR}/${BRANCH_NAME}/${REPO_NAME}/ && pip3 install -r requirements.txt'
                slackSend color: "good", message: "Success building the application."
            }
        }
        // stage("scan") {
        //     steps {
        //         echo 'Scanning code for vulnerabilities.'
        //         slackSend color: "warning", message: "Scanning code for vulnerabilities on ${REPO_NAME}/${BRANCH_NAME}..."
        //         snykSecurity(
        //             snykInstallation: 'snyk-latest',
        //             snykTokenId: "${SNYK_ID}",
        //             failOnIssues: "false",
        //         )
        //         slackSend color: "good", message: "Success scanning the code."
        //     }
        // }
    }
    post {
        success {
            echo 'The pipeline completed successfully.'
            slackSend color: "good", message: "The pipeline completed successfully. https://api.lcubestudios.io/${BRANCH_NAME}/${REPO_NAME}/"
        }
        failure {
            echo 'pipeline failed, at least one step failed'
            slackSend color: "danger", message: "Pipeline failed, at least one step failed. Check Jenkins console https://jenkins.lcubestudios.io/job/${REPO_NAME}/job/${BRANCH_NAME}/"
        }
    }      
}
