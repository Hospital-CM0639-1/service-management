# MANAGEMENT SERVICE

### Setup
1. Download "dev.decrypt.private.php" key from keys-repo and put it inside of config/secrets/dev
2. Download "public.pem" and "private.pem" jwt keys from keys-repo and put it inside config/jwt
3. Eventually, you can change docker port inside of docker-composer.yaml (default 20001)
4. Launch the setup with:
    ```sh
    docker compose -f PATH_TO_REPO/docker-compose.yaml -p service-management up -d
    ```
5. Execute PATH_TO_REPO/docker-start.sh (or one by one) to install dependencies and execute migrations (I suggest to execute them every time after a pull)
   NB: DO NOT EXECUTE MIGRATIONS IF YOU HAVE EXECUTE SCRIPT IN DATABASE REPOSITORY
6. Check .env.dev.local and .env.local.php to see if DATABASE_ params are correct, otherwise change in both files.
7. The service is reachable to http://127.0.0.1:DOCKER_PORT
8. To check if it works correctly, call http://127.0.0.1:DOCKER_PORT/api/v1/management-service/login in POST with following json body: {"username": "admin", "password": "admin"}



### API Token Generation
1. Execute the following command to generate a token for you service or api gateway:
   ```sh
   docker exec --workdir /var/www/html service-management php bin/console api:generate-api-token --name=INSERT_API_TOKEN_NAME
   ```
2. The given token must be inserted in the requests to _"api/gateway/validate-user-token"_ and _"api/service/validate-user-token"_ endpoints under header name **"API-Token"**


### API Definition

#### PRELIMINARY INFORMATION
1. The input for GET path is query params, for the other ones is request body.
2. For each path, in case of an error, the following JSON response will be returned:
   ```
   {
      "message": string; // the message to print
      "code": number; // HTTP Response code
      ... // other fields for debugging
   }
   ```
3. Password regex: https://uibakery.io/regex-library/password-regex-php
---

#### LOGIN

- **METHOD:** POST
- **PATH:** login
- **INPUT:**
 ```
{
     username: string;
     password: string;
}
```
- **OUTPUT:**
 ```
{
     id: number;
     firstName: string;
     lastName: string;
     email: string;
     username: string;
     type: string; // admin | staff
     lastLogin: null | string;
     expiredPassword: boolean; // if true, force user to change password
     token: string; // the JWT Token
     staffInfo: null | { // only if type == staff
          role: string;
          department: null | string;
          specialization: null | string;
          hireDate: string;
          phone: null | string;
     },
     patientInfo: null | { // only if type == patient
          dateOfBirth: string;
          gender: string; // Male | Female
          contactNumber: string;
          emergencyContactName: string;
          emergencyContactNumber: string;
          address: string;
          insuranceProvider: string;
          insurancePolicyNumber: string;
     }
}

```

---

#### LOGOUT

- **METHOD:** POST
- **PATH:** logout
- **USER TYPE:** admin|staff
- **HEADER:**
 ```
- Authorization: Bearer JWT_TOKEN
```
- **INPUT:**
 ```
```

- **OUTPUT:**
 ```
```

---

#### LOGGED USER INFO 

- **METHOD:** GET
- **PATH:** user/logged
- **USER TYPE:** admin|staff
- **HEADER:**
 ```
- Authorization: Bearer JWT_TOKEN
```
- **INPUT:**

 ```
```

- **OUTPUT:**
 ```
{
     id: number;
     firstName: string;
     lastName: string;
     email: string;
     username: string;
     type: string; // admin | staff
     lastLogin: null | string;
     expiredPassword: boolean; // if true, force user to change password
     token: string; // the JWT Token
     staffInfo: null | { // only if type == staff
          role: string;
          department: null | string;
          specialization: null | string;
          hireDate: string;
          phone: null | string;
     },
     patientInfo: null | { // only if type == patient
          dateOfBirth: string;
          gender: string; // Male | Female
          contactNumber: string;
          emergencyContactName: string;
          emergencyContactNumber: string;
          address: string;
          insuranceProvider: string;
          insurancePolicyNumber: string;
     }
}
```

---

#### USER LIST

- **METHOD:** GET
- **PATH:** user
- **USER TYPE:** admin
- **HEADER:**
 ```
- Authorization: Bearer JWT_TOKEN
```
- **INPUT:**
 ```
{
     status: null | boolean; // active | not_active | all
     role: null | string; // NURSE | DOCTOR | SECRETARY
}
```

- **OUTPUT:**
 ```
{
     id: number;
     firstName: string;
     lastName: string;
     email: string;
     username: string;
     active: boolean;
     type: string; // admin | staff
     role?: string; // nurse | doctor | secretary
}[]
```

#### USER DETAIL

- **METHOD:** GET
- **PATH:** user/{id}
- **USER TYPE:** admin
- **HEADER:**
 ```
- Authorization: Bearer JWT_TOKEN
```
- **INPUT:**

 ```
```

- **OUTPUT:**
 ```
{
     id: number;
     firstName: string;
     lastName: string;
     email: string;
     username: string;
     type: string; // admin | staff
     active: boolean;
     staffInfo: null | { // only if type == staff
          role: string;
          department: null | string;
          specialization: null | string;
          hireDate: string;
          phone: null | string;
     },
     patientInfo: null | { // only if type == patient
          dateOfBirth: string;
          gender: string; // Male | Female
          contactNumber: string;
          emergencyContactName: string;
          emergencyContactNumber: string;
          address: string;
          insuranceProvider: string;
          insurancePolicyNumber: string;
     }
}
```

---

#### CREATE USER

- **METHOD:** POST
- **PATH:** user
- **USER TYPE:** admin
- **HEADER:**
 ```
- Authorization: Bearer JWT_TOKEN
```
- **INPUT:**

 ```
{
     firstName: string;
     lastName: string;
     email: string;
     username: string;
     type: string; // admin | staff
     staffInfo: null | { // only if type == staff
          role: string;
          department: null | string;
          specialization: null | string;
          hireDate: string;
          phone: null | string;
     },
     patientInfo: null | { // only if type == patient
          dateOfBirth: string;
          gender: string; // Male | Female
          contactNumber: string;
          emergencyContactName: string;
          emergencyContactNumber: string;
          address: string;
          insuranceProvider: string;
          insurancePolicyNumber: string;
     }
}
```

- **OUTPUT:**
 ```
```

---

#### EDIT USER

- **METHOD:** PUT
- **PATH:** user/{id}
- **USER TYPE:** admin
- **HEADER:**
 ```
- Authorization: Bearer JWT_TOKEN
```
- **INPUT:**

 ```
{
     email: string;
     firstName: string;
     lastName: string;
     staffInfo: null | { // only if type == staff
          role: string;
          department: null | string;
          specialization: null | string;
          hireDate: string;
          phone: null | string;
     },
     patientInfo: null | { // only if type == patient
          dateOfBirth: string;
          gender: string; // Male | Female
          contactNumber: string;
          emergencyContactName: string;
          emergencyContactNumber: string;
          address: string;
          insuranceProvider: string;
          insurancePolicyNumber: string;
     }
}
```

- **OUTPUT:**
 ```
```

---

#### ENABLE / DISABLE USER

- **METHOD:** PUT
- **PATH:** user/{id}/{enable|disable}
- **USER TYPE:** admin
- **HEADER:**
 ```
- Authorization: Bearer JWT_TOKEN
```
- **INPUT:**
 ```
```
- **OUTPUT:**
 ```
```

---

#### USER CHANGE PASSWORD

- **METHOD:** POST
- **PATH:** user/change-password
- **USER TYPE:** admin|staff
- **HEADER:**
 ```
- Authorization: Bearer JWT_TOKEN
```
- **INPUT:**
 ```
{
   oldPassword: string;
   newPassword: string;
   repeatedPassword: string;
}
```
- **OUTPUT:**
 ```
```

---

#### USER CHANGE PASSWORD TO ANOTHER ONE

- **METHOD:** POST
- **PATH:** user/{id}/change-password
- **USER TYPE:** admin
- **HEADER:**
 ```
- Authorization: Bearer JWT_TOKEN
```
- **INPUT:**
 ```
{
   newPassword: string;
   repeatedPassword: string;
}
```
- **OUTPUT:**
 ```
```

---

#### USER TOKEN VALIDATION: SERVICE SIDE

- **METHOD:** POST
- **PATH:** api/service/validate/user-token
- **USER TYPE:** api
- **HEADER:**
 ```
- API-Token: TOKEN
```
- **INPUT:**
 ```
{
   token: string;
}
```
- **OUTPUT:**
 ```
{
     valid: boolean;
     invalidReason: null | string; // populated only if valid is false
     userInfo: null | { // populated only if valid is true
        id: number;
        username: string;
        type: string; // admin | staff
        role?: string; // nurse | doctor | secretary
     }
}
```


---

#### USER TOKEN VALIDATION: API GATEWAY SIDE

- **METHOD:** POST
- **PATH:** api/gateway/validate/user-token
- **USER TYPE:** api
- **HEADER:**
 ```
- API-Token: TOKEN
```
- **INPUT:**
 ```
{
   token: string;
}
```
- **OUTPUT:**
 ```
{
     valid: boolean;
     invalidReason: null | string; // populated only if valid is false
}
```