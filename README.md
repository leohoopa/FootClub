# FootClub
- Installation des dépendances PHP et génération de l'autoloader :
    ```sh
    composer install
    ```
-Convention de nommage : camelCase
-Utilisation du framework Tailwwindcss + DaisyUi
    ->pour avoir le css:
        ->. /tailwindcss -i input.css -o output.css --watch
            # For Windows
            tailwindcss.exe -i input.css -o output.css --watch
-Arborescence:
FootClub/
├── public/                         # Fichiers accessibles par le navigateur
│   ├── assets/                     # CSS et fichiers Tailwind/DaisyUI
│   │   ├── daisyui-theme.mjs
│   │   ├── daisyui.mjs
│   │   ├── input.css
│   │   └── output.css
│   ├── pages/                      # Pages de l'application
│   └── index.php                   # Page d'accueil
├── src/
│   ├── Controller/                 # Validation et traitement des formulaires
│   ├── Enum/                       # Énumérations
│   ├── Model/                      # Objets métier
│   │   └── db/                     # Connexion et accès aux données
│   └── Tests/                      # Données de test
├── vendor/                         # Dépendances installées par Composer
├── .env                            # Configuration locale de la base (non versionnée)
├── .env.example                    # Exemple de configuration
├── .gitignore
├── composer.json                   # Dépendances et autoload PHP
├── composer.lock                   # Versions exactes des dépendances
├── footclub.sql                    # Création de la base et des tables
└── README.md