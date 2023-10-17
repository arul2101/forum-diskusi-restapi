# User Api Spec

## Register User Api

Endpoint: GET "/api/users"

Request Body : 
 ```json
 {
    "username": "arul2101",
    "password": "rahasia",
    "name": "Arul Ganteng"
 }
 ```

 Response Body Success : 
 ```json
 {
    "data" : {
      "id": 1,
      "username": "arul2101",
      "name": "Arul Ganteng"
    }
 }
 ```