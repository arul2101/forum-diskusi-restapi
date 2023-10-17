# RESTFUL API FORUM DISKUSI

<details markdown="1">
  <summary>Table of Contents</summary>

-   [USER API SPEC](#user)
    *   [REGISTER USER API](#register-user)
    *   [LOGIN USER API](#login-user)
    *   [GET USER API](#get-user)
    *   [UPDATE USER API](#update-user)
    *   [LOGOUT USER API](#logout-user)

-   [POST API SPEC](#post)
    *   [CREATE POST API](#create-post)
    *   [GET POST API](#get-post)
    *   [SEARCH POST API](#search-post)
    *   [UPDATE POST API](#update-post)
    *   [DELETE POST API](#delete-post)

-   [COMMENT API SPEC](#comment)
    *   [CREATE COMMENT API](#create-comment)
    *   [GET COMMENT API](#get-comment)
    *   [UPDATE COMMENT API](#update-comment)
    *   [DELETE COMMENT API](#delete-comment)
    *   [SEARCH COMMENT API](#search-comment)

</details>


## 🔥🔥 USER API SPEC <a id="user"></a>

### ✅ Register User API <a id="register-user"></a>
Endpoint: POST "/api/users"

Request Body: 

```json
{
  "username": "arul2101",
  "password": "rahasia",
  "name": "Arul Ganteng"
}
```

Response Body Success: 

```json
{
  "data": {
    "id": 1,
    "username": "arul2101",
    "name": "Arul Ganteng"
  }
}
```

Response Body Failed:
```json
{
  "errors": {
    "username": [
      "username already registered"
    ]
  }
}
```

### ✅ Login User API <a id="login-user"></a>
Endpoint: POST "/api/users/login"

Request Body: 

```json
{
  "username": "arul2101",
  "password": "rahasia"
}
```
Response Body Success: 

```json
{
  "data": {
    "id": 1,
    "username": "arul2101",
    "name": "Arul Ganteng",
    "token": "unik-token"
  }
}
```

Response Body Failed:
```json
{
  "errors": {
    "message": [
      "username or password is wrong"
    ]
  }
}
```

### ✅ GET User API <a id="get-user"></a>
Endpoint: GET "/api/users/current"

Headers: 
- Authorization: unik-token

Response Body Success: 

```json
{
  "data": {
    "id": 1,
    "username": "arul2101",
    "name": "Arul Ganteng",
    "token": "unik-token"
  }
}
```

Response Body Failed: 

Response Body Failed:
```json
{
  "errors": {
    "message": [
      "unauthorized"
    ]
  }
}
```

### ✅ Update User API <a id="update-user"></a>
Endpoint: PATCH "/api/users/current"

Headers: 
- Authorization: unik-token

Request Body: 

```json
{
  "name": "Arul Ganteng Updated", //optional
  "password": "rahasia updated" //optional
}
```

Response Body Success: 
```json
{
  "data": {
    "id": 1,
    "username": "arul2101",
    "name": "Arul Ganteng"
  }
}
```

Response Body Failed:
```json
{
  "errors": {
    "message": [
      "unauthorized"
    ]
  }
}
```

### ✅ Logout User API <a id="logout-user"></a>
Endpoint: DELETE "/api/users/logout"

Headers: 
- Authorization: unik-token

Response Body Success: 
```json
{
  "data": true
}
```

Response Body Failed:
```json
{
  "errors": {
    "message": [
      "unauthorized"
    ]
  }
}
```

## 🔥🔥 POST API SPEC <a id="post"></a>

### ✅ Create Post API <a id="create-post"></a>
Endpoint: POST "/api/posts"

Headers: 
- Authorization: unik-token

Request Body: 

```json
{
  "title": "Ini adalah judul post",
  "body": "Ini adalah body post"
}
```

Response Body Success: 
```json
{
  "data": {
    "id": 1,
    "title": "Ini adalah judul post",
    "body": "Ini adalah body post",
    "createdAt": "2023-10-16T22:47:36.000000Z",
    "updatedAt": "2023-10-16T22:47:36.000000Z"
  }
}
```

Response Body Failed: 
```json
{
  "errors": {
    "title": [
      "The title field is required."
    ],
    "body": [
      "The body field is required."
    ]
  }
}
```

### ✅ GET Post API <a id="get-post"></a>
Endpoint: GET "/api/posts/{id}"

Headers: 
- Authorization: unik-token

Response Body Success: 
```json
{
  "data": {
    "id": 1,
    "title": "Ini adalah judul post",
    "body": "Ini adalah body post",
    "createdAt": "2023-10-16T22:47:36.000000Z",
    "updatedAt": "2023-10-16T22:47:36.000000Z"
  }
}
```

Response Body Failed: 
```json
{
  "errors": {
    "message": [
      "not found"
    ]
  }
}
```

### ✅ SEARCH Post API <a id="search-post"></a>
Endpoint: GET "/api/posts"

Headers: 
- Authorization: unik-token

Query Param:
- title: search by title using like, optional
- body: search by body using like, optional
- page: filter page, default 1
- size: filter size per page, default 10

Response Body Success: 
```json
{
  "data": [
    {
      "id": 1,
      "title": "Ini adalah judul post 1",
      "body": "Ini adalah body post 1",
      "createdAt": "2023-10-16T22:47:36.000000Z",
      "updatedAt": "2023-10-16T22:47:36.000000Z"
    },
    {
      "id": 2,
      "title": "Ini adalah judul post 2",
      "body": "Ini adalah body post 2",
      "createdAt": "2023-10-16T22:47:36.000000Z",
      "updatedAt": "2023-10-16T22:47:36.000000Z"
    },
    {
      "id": 3,
      "title": "Ini adalah judul post 3",
      "body": "Ini adalah body post 3",
      "createdAt": "2023-10-16T22:47:36.000000Z",
      "updatedAt": "2023-10-16T22:47:36.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/posts?page=1",
    "last": "http://localhost:8000/api/posts?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://localhost:8000/api/posts?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "path": "http://localhost:8000/api/posts",
    "per_page": 10,
    "to": 1,
    "total": 1
    }
}
```

Response Body Failed: 
```json
{
  "errors": {
    "message": [
      "unauthorized"
    ]
  }
}
```

### ✅ UPDATE Post API <a id="update-post"></a>
Endpoint: PUT "/api/posts/{id}"

Headers: 
- Authorization: unik-token

Request Body: 
```json
{
  "title": "Ini adalah title post 1 updated", //optional
  "body": "Ini adalah body post 1 updated", //optional
}
```

Response Body Success: 
```json
{
  "data": {
    "id": 1,
    "title": "Ini adalah judul post 1 updated",
    "body": "Ini adalah body post 1 updated",
    "createdAt": "2023-10-16T22:47:36.000000Z",
    "updatedAt": "2023-10-16T22:57:58.000000Z"
  }
}
```

Response Body Failed: 
```json
{
  "errors": {
    "message": [
      "not found"
    ]
  }
}
```

### ✅ DELETE Post API <a id="delete-post"></a>
Endpoint: DELETE "/api/posts/{id}"

Headers: 
- Authorization: unik-token

Response Body Success: 
```json
{
  "data": true
}
```

Response Body Failed: 
```json
{
  "errors": {
    "message": [
      "not found"
    ]
  }
}
```

## 🔥🔥 COMMENT API SPEC <a id="comment"></a>

### ✅ Create COMMENT API <a id="create-comment"></a>
Endpoint: POST "/api/posts/{idPost}/comments"

Headers: 
- Authorization: unik-token

Request Body:
```json
{
  "desc": "Ini adalah comment di post 1"
}
```

Response Body Success: 
```json
{
  "data": {
    "id": 1,
    "desc": "Ini adalah comment di post 1",
    "user_id": 4,
    "post_id": 10,
    "createdAt": "2023-10-16T22:47:36.000000Z",
    "updatedAt": "2023-10-16T22:47:36.000000Z",
  }
}
```

Response Body Failed: 
```json
{
  "errors": {
    "message": [
      "not found"
    ]
  }
}
```

### ✅ GET COMMENT API <a id="get-comment"></a>
Endpoint: POST "/api/posts/{idPost}/comments/{idComment}"

Headers: 
- Authorization: unik-token

Response Body Success: 
```json
{
  "data": {
    "id": 1,
    "desc": "Ini adalah comment di post 1",
    "user_id": 4,
    "post_id": 10,
    "createdAt": "2023-10-16T22:47:36.000000Z",
    "updatedAt": "2023-10-16T22:47:36.000000Z",
  }
}
```

Response Body Failed: 
```json
{
  "errors": {
    "message": [
      "not found"
    ]
  }
}
```

### ✅ UPDATE COMMENT API <a id="update-comment"></a>
Endpoint: PATCH "/api/posts/{idPost}/comments/{idComment}"

Headers: 
- Authorization: unik-token

Request Body:
```json
{
  "desc": "Ini adalah comment di post 1 updated"
}
```

Response Body Success: 
```json
{
  "data": {
    "id": 1,
    "desc": "Ini adalah comment di post 1 updated",
    "user_id": 4,
    "post_id": 10,
    "createdAt": "2023-10-16T22:47:36.000000Z",
    "updatedAt": "2023-10-16T22:47:58.000000Z",
  }
}
```

Response Body Failed: 
```json
{
  "errors": {
    "message": [
      "not found"
    ]
  }
}
```

### ✅ DELETE COMMENT API <a id="delete-comment"></a>
Endpoint: DELETE "/api/posts/{idPost}/comments/{idComment}"

Headers: 
- Authorization: unik-token

Response Body Success: 
```json
{
  "data": true
}
```

Response Body Failed: 
```json
{
  "errors": {
    "message": [
      "not found"
    ]
  }
}
```

### ✅ SEARCH COMMENT API <a id="search-comment"></a>
Endpoint: GET "/api/posts/{idPost}/comments"

Headers: 
- Authorization: unik-token

Query Param:
- desc: search by desc using like, optional
- page: filter page, default 1
- size: filter size per page, default 10

Response Body Success: 
```json
{
  "data": [
    {
      "id": 1,
      "desc": "Ini adalah comment di post 1",
      "user_id": 4,
      "post_id": 10,
      "createdAt": "2023-10-16T22:47:36.000000Z",
      "updatedAt": "2023-10-16T22:47:36.000000Z",
    },
    {
      "id": 2,
      "desc": "Ini adalah comment di post 2",
      "user_id": 4,
      "post_id": 10,
      "createdAt": "2023-10-16T22:47:36.000000Z",
      "updatedAt": "2023-10-16T22:47:36.000000Z",
    },
    {
      "id": 3,
      "desc": "Ini adalah comment di post 3",
      "user_id": 4,
      "post_id": 10,
      "createdAt": "2023-10-16T22:47:36.000000Z",
      "updatedAt": "2023-10-16T22:47:36.000000Z",
    },
    {
      "id": 4,
      "desc": "Ini adalah comment di post 4",
      "user_id": 4,
      "post_id": 10,
      "createdAt": "2023-10-16T22:47:36.000000Z",
      "updatedAt": "2023-10-16T22:47:36.000000Z",
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/posts/257/comments?page=1",
    "last": "http://localhost:8000/api/posts/257/comments?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://localhost:8000/api/posts/257/comments?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "path": "http://localhost:8000/api/posts/257/comments",
    "per_page": 10,
    "to": 5,
    "total": 5
  }
}
```

Response Body Failed: 
```json
{
  "errors": {
    "message": [
      "not found"
    ]
  }
}
```


