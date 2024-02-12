# Utilisez une image de base appropriée
From node:docker

# Copiez le code source dans le conteneur
Copy . index.php

# Définissez le répertoire de travail
WORKDIR /

# Installez les dépendances

#Exposez le port nécessaire 
EXPOSE 3000

#Commande par défaut pour démarrer l'application 



