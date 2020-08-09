# How to run:

1. docker compose up -d
2. docker exec -it currencies_php_1 bash
3. php yii migrate
4. php yii currencies/update
5. Done :)

#Example: 

GET http://localhost:12002/currency - list of all currencies in db 

GET http://localhost:12002/currency?per-page=1 - list of all currencies in db with pagination 

GET http://localhost:12002/currency/1 - currency by id
