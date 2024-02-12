 pipeline {
    agent any
    
    stages {
        stage('Checkout') {
            steps {
                git 'https://github.com/ANTONIODavidSIO/ProjetGroupeFCD.git'
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
 