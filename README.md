# Installation steps

- git clone https://github.com/balazs1396/cafeteria-administration.git
- cd cafeteria-administration/api/
- copy .env file with configs in docker-compose file
- composer install
- ./vendor/bin/sail up
- ./vendor/bin/sail artisan migrate
- ./vendor/bin/sail artisan db:seed
- cd ..
- cd ui/
- npm install
- npm run dev
- open: http://localhost:8081

# If you change the laravel.test container port, modify it too in.: ./ui/src/repositories/Base.js
