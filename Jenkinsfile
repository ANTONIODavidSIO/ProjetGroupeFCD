 pipeline {
    agent any
    
    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }
        
        stage('Build') {
            steps {
                sh 'docker build -t monimage .'
            }
        }
        
        stage('Test') {
            steps {
                // Ajoutez ici vos tests de qualité de code si nécessaire
            }
        }
        
        stage('Deploy') {
            steps {
                sh 'docker-compose up -d'
            }
        }
    }
}
 