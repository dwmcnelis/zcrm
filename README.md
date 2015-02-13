
# zCRM

## Overview

**zCRM**, Customer Relationship Management backend implemented with Zend Framework 2

## Project Structure
**zCRM**, is structured from a Zend Framework 2 skeleton application and its dependencies are managed with php composer.phar.

## Web Routes
route | Purpose
------------ | -------------
/customer | Index customers
customer/edit/[:id] | Edit customer


## Implemetation Notes

### Modules

#### Customer Module

MVC for Customer Objects

##### Classes

Class | Purpose
------------ | -------------
CustomerController | Web controller for Customer Routes
CustomerForm | Form for Customer Objects
Customer | Model for Customer Objects
CustomerTable |  Table gateway for Customer Object Persistance


#### CustomerAPI Module

API controller based on AbstractRestfulController

##### Classes

Class | Purpose
------------ | -------------
CustomerAPIController | API controller for Customer Routes

### Database
#### SQLite
Stored in data/database.db
#### Tables
Table | Purpose
------------ | -------------
customer | customer records

Column | Properties
------------ | -------------
id | INTEGER PRIMARY KEY AUTOINCREMENT
email | CHAR(254) NOT NULL
company | CHAR(100) NOT NULL
name_first | CHAR(40) NOT NULL
name_last | CHAR(40) NOT NULL
phone | CHAR(40) NOT NULL
address | CHAR(40) NOT NULL
city | CHAR(40) NOT NULL
state | CHAR(20) NOT NULL
postal | CHAR(11) NOT NUL

### Cross Domain
#### XDomain.js
Used in conjunction with eCRM, an alternate Ember.js front end.
##### Library
Library served from /public/js/xdomain.min.js
##### Configuration
Backend is configured as a slave from /public/xdomain/proxy.html It identifies which masters are allowed cross domain access to the backend.

## API Authorization
Access is restricted to API routes using JSON Web Tokens passed through an X-AUTH-TOKEN for each request. Only one valid token is authorized.  Please use the following:

header | Value
------------ | -------------
X-AUTH-TOKEN | eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI5NzUzIiwiZXhwIjpudWxsfQ.sDg1qCCcKIo_290O6jIk7fnYDU6b6by0LweFYlW6iqE


## API Routes
method | route | Purpose
----------- | ------------ | -------------
GET | /customer | Index customers
GET | /customer/email/pattern | Search customers by email
GET | /customer/name/pattern | Search customers by email
GET | /customer | Index customers
GET | customer/edit/[:id] | Edit customer



### API via bash curl scripts (bin/*)

#### Options (OPTIONS /api/v1/customers)

```
➜  bin  ./options
* Adding handle: conn: 0x7ffdbb00d000
* Adding handle: send: 0
* Adding handle: recv: 0
* Curl_addHandleToPipeline: length: 1
* - Conn 0 (0x7ffdbb00d000) send_pipe: 1, recv_pipe: 0
* About to connect() to localhost port 5002 (#0)
*   Trying ::1...
*   Trying 127.0.0.1...
* Connected to localhost (127.0.0.1) port 5002 (#0)
> OPTIONS /api/v1/customers HTTP/1.1
> User-Agent: curl/7.30.0
> Host: localhost:5002
> Accept: */*
>
< HTTP/1.1 200 OK
* Server nginx/1.6.2 is not blacklisted
< Server: nginx/1.6.2
< Date: Fri, 13 Feb 2015 20:20:37 GMT
< Content-Type: text/html; charset=UTF-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-Powered-By: PHP/5.6.5
< Access-Control-Allow-Origin: *
< Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE
< Access-Control-Allow-Headers: X-AUTH-TOKEN
<
* Connection #0 to host localhost left intact
➜  bin
```

#### Unauthorized (no valid X-AUTH-TOKEN)

```
➜  bin  ./unauthorized
* Adding handle: conn: 0x7fe4e2003a00
* Adding handle: send: 0
* Adding handle: recv: 0
* Curl_addHandleToPipeline: length: 1
* - Conn 0 (0x7fe4e2003a00) send_pipe: 1, recv_pipe: 0
* About to connect() to localhost port 5002 (#0)
*   Trying ::1...
*   Trying 127.0.0.1...
* Connected to localhost (127.0.0.1) port 5002 (#0)
> GET /api/v1/customers HTTP/1.1
> User-Agent: curl/7.30.0
> Host: localhost:5002
> Accept: */*
>
< HTTP/1.1 401 Unauthorized
* Server nginx/1.6.2 is not blacklisted
< Server: nginx/1.6.2
< Date: Fri, 13 Feb 2015 20:11:08 GMT
< Content-Type: application/json; charset=utf-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-Powered-By: PHP/5.6.5
<
* Connection #0 to host localhost left intact
{"error":"Unauthorized","detail":"Not authorized for access","status":"401"}%
```

#### Index (GET /api/v1/customers)

```
➜  bin  ./index
* Adding handle: conn: 0x7fc8d0803a00
* Adding handle: send: 0
* Adding handle: recv: 0
* Curl_addHandleToPipeline: length: 1
* - Conn 0 (0x7fc8d0803a00) send_pipe: 1, recv_pipe: 0
* About to connect() to localhost port 5002 (#0)
*   Trying ::1...
*   Trying 127.0.0.1...
* Connected to localhost (127.0.0.1) port 5002 (#0)
> GET /api/v1/customers HTTP/1.1
> User-Agent: curl/7.30.0
> Host: localhost:5002
> Accept: */*
> X-AUTH-TOKEN: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI5NzUzIiwiZXhwIjpudWxsfQ.sDg1qCCcKIo_290O6jIk7fnYDU6b6by0LweFYlW6iqE
>
< HTTP/1.1 200 OK
* Server nginx/1.6.2 is not blacklisted
< Server: nginx/1.6.2
< Date: Fri, 13 Feb 2015 20:14:22 GMT
< Content-Type: application/json; charset=utf-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-Powered-By: PHP/5.6.5
< Access-Control-Allow-Origin: *
< Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE
< Access-Control-Allow-Headers: X-AUTH-TOKEN
<
{"customers":[{"id":"1","email":"mikel@franeckilang.name","company":"XXAcme","name_first":"Lyla","name_last":"Heidenreich","name_full":"Heidenreich, Lyla","phone":"785-613-0946","address":"123 Main Street","city":"Woodbridge","state":"CT","postal":"01233"},{"id":"2","email":"piper@trantow.name","company":"Acme","name_first":"Brisa","name_last":"Aufderhar","name_full":"Aufderhar, Brisa","phone":"245.418.5015 x42992","address":"721 Main Street","city":"Wayward","state":"CT","postal":"06525"},{"id":"3","email":"jeramy@bauch.name","company":"Acme","name_first":"Garnett","name_last":"Bruen","name_full":"Bruen, Garnett","phone":"166.283.0666 x484","address":"93 Main Street","city":"Albright","state":"CT","postal":"06525"},{"id":"4","email":"rudy_wolf@armstrong.name","company":"Acme","name_first":"Erwin","name_last":"David","name_full":"David, Erwin","phone":"1-890-736-2240 x1002","address":"4 Main Street","city":"Durry","state":"CT","postal":"06525"},{"id":"5","email":"enid_kirlin@rolfson.com","company":"Acme","name_first":"Isobel","name_last":"Ullrich","name_full":"Ullrich, Isobel","phone":"1-428-223-3062","address":"230 Main Street","city":"Rainbox","state":"CT","postal":"06525"},{"id":"6","email":"forest@crist.org","company":"Acme","name_first":"Dean","name_last":"Borer","name_full":"Borer, Dean","phone":"(104) 830-1928 x28749","address":"73 Main Street","city":"Bratton","state":"CT","postal":"06525"},{"id":"7","email":"friedrich_schamberger@hoppe.org","company":"Acme","name_first":"Juvenal","name_last":"Predovic","name_full":"Predovic, Juvenal","phone":"785.119.6923","address":"22 Main Street","city":"Foreseen","state":"CT","postal":"06525"},{"id":"8","email":"nina_toy@feest.biz","company":"Acme","name_first":"Lavinia","name_last":"Gerhold","name_full":"Gerhold, Lavinia","phone":"1-576-758-4070","address":"93 Main Street","city":"Skylar","state":"CT","postal":"06525"},{"id":"9","email":"ashleigh@wuckert.info","company":"Acme","name_first":"Dorcas","name_last":"Conroy","name_full":"Conroy, Dorcas","phone":"224.924.5933","address":"3112 Main Street","city":"Donsen","state":"CT","postal":"06525"},{"id":"10","email":"delaney_kuphal@stiedemann.biz","company":"Acme","name_first":"Duane","name_last":"Schulist","name_full":"Schulist, Duane","phone":"(761) 028-9110","address":"80 Main Street","city":"Palmer","state":"CT","postal":"06525"},{"id":"11","email":"ofelia_hand@kozeybrakus.name","company":"Test","name_first":"Ali-Baba","name_last":"Reynolds","name_full":"Reynolds, Ali-Baba","phone":"1-450-845-7825 x615","address":"99 Main Street","city":"Rowan","state":"CT","postal":"06525"},{"id":"12","email":"gertrude@stromanvonrueden.biz","company":"Acme","name_first":"Dorothea","name_last":"Ward","name_full":"Ward, Dorothea","phone":"1-556-718-0368","address":"99 Main Street","city":"Titan","state":"CT","postal":"06525"},{"id":"13","email":"dimitri.pfeffer@schamberger.biz","company":"Acme","name_first":"Robbie","name_last":"Tremblay","name_full":"Tremblay, Robbie","phone":"119.407.9794 x2467","address":"66 Main Street","city":"Calburg","state":"CT","postal":"06525"},{"id":"14","email":"dolores_schamberger@fritschlittel.org","company":"Acme","name_first":"Kenyatta","name_last":"Schulist","name_full":"Schulist, Kenyatta","phone":"1-198-122-4317 x6817","address":"23 Main Street","city":"Orland","state":"CT","postal":"06525"},{"id":"15","email":"amya.price@feestokuneva.com","company":"Acme","name_first":"Chyna","name_last":"Huels","name_full":"Huels, Chyna","phone":"548-995-5023 x7892","address":"985 Main Street","city":"Rolly","state":"CT","postal":"06525"},{"id":"16","email":"corene@purdywiza.net","company":"Acme","name_first":"Myrl","name_last":"Weimann","name_full":"Weimann, Myrl","phone":"283-558-0577 x72098","address":"161 Main Street","city":"Loon Lake","state":"CT","postal":"06525"},{"id":"17","email":"layne@marksleannon.biz","company":"Acme","name_first":"Osvaldo","name_last":"Streich","name_full":"Streich, Osvaldo","phone":"892-451-3450 x4119","address":"272 Main Street","city":"Oxbox","state":"CT","postal":"06525"},{"id":"18","e* Connection #0 to host localhost left intact
mail":"oscar_rosenbaum@binsratke.info","company":"Acme","name_first":"Lenora","name_last":"Feest","name_full":"Feest, Lenora","phone":"(479) 220-3467","address":"2674 Main Street","city":"Sinter","state":"CT","postal":"06525"},{"id":"19","email":"haan.dickens@nolankshlerin.net","company":"Acme","name_first":"Bobby","name_last":"Deckow","name_full":"Deckow, Bobby","phone":"1-396-752-6693 x21200","address":"1232 Main Street","city":"Balbryne","state":"CT","postal":"06525"},{"id":"20","email":"raul.conroy@wuckert.biz","company":"Acme","name_first":"Yolanda","name_last":"Leffler","name_full":"Leffler, Yolanda","phone":"(552) 913-5511 x9187","address":"52 Main Street","city":"Tailor","state":"CT","postal":"06525"},{"id":"53","email":"davemcnelis@gmail.com","company":"Work","name_first":"David","name_last":"McNelis","name_full":"McNelis, David","phone":"2039153177","address":"49 Fox Hill Road","city":"Woodbridge","state":"CT","postal":"06525"},{"id":"54","email":"davdmcnelis@gmail.com","company":"","name_first":"Dave","name_last":"McNelis","name_full":"McNelis, Dave","phone":"203-123-9876","address":"123 Main Street","city":"Woodbridge","state":"CT","postal":"01233"}]}%                                                  ➜  bin
```

#### Search Email (GET /api/v1/customers/q/davemcnelis%40gmail.com)

```
➜  bin  ./search_email
* Adding handle: conn: 0x7faa83003a00
* Adding handle: send: 0
* Adding handle: recv: 0
* Curl_addHandleToPipeline: length: 1
* - Conn 0 (0x7faa83003a00) send_pipe: 1, recv_pipe: 0
* About to connect() to localhost port 5002 (#0)
*   Trying ::1...
*   Trying 127.0.0.1...
* Connected to localhost (127.0.0.1) port 5002 (#0)
> GET /api/v1/customers/email/davemcnelis%40gmail.com HTTP/1.1
> User-Agent: curl/7.30.0
> Host: localhost:5002
> Accept: */*
> X-AUTH-TOKEN: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI5NzUzIiwiZXhwIjpudWxsfQ.sDg1qCCcKIo_290O6jIk7fnYDU6b6by0LweFYlW6iqE
>
< HTTP/1.1 200 OK
* Server nginx/1.6.2 is not blacklisted
< Server: nginx/1.6.2
< Date: Fri, 13 Feb 2015 20:16:20 GMT
< Content-Type: application/json; charset=utf-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-Powered-By: PHP/5.6.5
< Access-Control-Allow-Origin: *
< Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE
< Access-Control-Allow-Headers: X-AUTH-TOKEN
<
* Connection #0 to host localhost left intact
{"customers":[{"id":"53","email":"davemcnelis@gmail.com","company":"Work","name_first":"David","name_last":"McNelis","name_full":"McNelis, David","phone":"2039153177","address":"49 Fox Hill Road","city":"Woodbridge","state":"CT","postal":"06525"}]}%                                                                                                       ➜  bin
```

#### Search Name (GET /api/v1/customers/q/david)

```
➜  bin  ./search_name
* Adding handle: conn: 0x7f9d3c003a00
* Adding handle: send: 0
* Adding handle: recv: 0
* Curl_addHandleToPipeline: length: 1
* - Conn 0 (0x7f9d3c003a00) send_pipe: 1, recv_pipe: 0
* About to connect() to localhost port 5002 (#0)
*   Trying ::1...
*   Trying 127.0.0.1...
* Connected to localhost (127.0.0.1) port 5002 (#0)
> GET /api/v1/customers/name/david HTTP/1.1
> User-Agent: curl/7.30.0
> Host: localhost:5002
> Accept: */*
> X-AUTH-TOKEN: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI5NzUzIiwiZXhwIjpudWxsfQ.sDg1qCCcKIo_290O6jIk7fnYDU6b6by0LweFYlW6iqE
>
< HTTP/1.1 200 OK
* Server nginx/1.6.2 is not blacklisted
< Server: nginx/1.6.2
< Date: Fri, 13 Feb 2015 20:17:15 GMT
< Content-Type: application/json; charset=utf-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-Powered-By: PHP/5.6.5
< Access-Control-Allow-Origin: *
< Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE
< Access-Control-Allow-Headers: X-AUTH-TOKEN
<
* Connection #0 to host localhost left intact
{"customers":[{"id":"4","email":"rudy_wolf@armstrong.name","company":"Acme","name_first":"Erwin","name_last":"David","name_full":"David, Erwin","phone":"1-890-736-2240 x1002","address":"4 Main Street","city":"Durry","state":"CT","postal":"06525"},{"id":"53","email":"davemcnelis@gmail.com","company":"Work","name_first":"David","name_last":"McNelis","name_full":"McNelis, David","phone":"2039153177","address":"49 Fox Hill Road","city":"Woodbridge","state":"CT","postal":"06525"}]}%                                              ➜  bin
```

#### Create (POST /api/v1/customers)

```
➜  bin  ./create
* Adding handle: conn: 0x7fe53b003a00
* Adding handle: send: 0
* Adding handle: recv: 0
* Curl_addHandleToPipeline: length: 1
* - Conn 0 (0x7fe53b003a00) send_pipe: 1, recv_pipe: 0
* About to connect() to localhost port 5002 (#0)
*   Trying ::1...
*   Trying 127.0.0.1...
* Connected to localhost (127.0.0.1) port 5002 (#0)
> POST /api/v1/customers HTTP/1.1
> User-Agent: curl/7.30.0
> Host: localhost:5002
> Accept: */*
> X-AUTH-TOKEN: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI5NzUzIiwiZXhwIjpudWxsfQ.sDg1qCCcKIo_290O6jIk7fnYDU6b6by0LweFYlW6iqE
> Content-Type: application/json
> Content-Length: 210
>
* upload completely sent off: 210 out of 210 bytes
< HTTP/1.1 200 OK
* Server nginx/1.6.2 is not blacklisted
< Server: nginx/1.6.2
< Date: Fri, 13 Feb 2015 20:18:42 GMT
< Content-Type: application/json; charset=utf-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-Powered-By: PHP/5.6.5
< Access-Control-Allow-Origin: *
< Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE
< Access-Control-Allow-Headers: X-AUTH-TOKEN
<
* Connection #0 to host localhost left intact
{"customer":{"id":"55","email":"davdmcnelis@gmail.com","company":"","name_first":"Dave","name_last":"McNelis","name_full":"McNelis, Dave","phone":"203-123-9876","address":"123 Main Street","city":"Woodbridge","state":"CT","postal":"01233"}}%
➜  bin
```

#### Read (GET /api/v1/customers:id)

```
➜  bin  ./read
* Adding handle: conn: 0x7ff8cb803a00
* Adding handle: send: 0
* Adding handle: recv: 0
* Curl_addHandleToPipeline: length: 1
* - Conn 0 (0x7ff8cb803a00) send_pipe: 1, recv_pipe: 0
* About to connect() to localhost port 5002 (#0)
*   Trying ::1...
*   Trying 127.0.0.1...
* Connected to localhost (127.0.0.1) port 5002 (#0)
> GET /api/v1/customers/1 HTTP/1.1
> User-Agent: curl/7.30.0
> Host: localhost:5002
> Accept: */*
> X-AUTH-TOKEN: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI5NzUzIiwiZXhwIjpudWxsfQ.sDg1qCCcKIo_290O6jIk7fnYDU6b6by0LweFYlW6iqE
>
< HTTP/1.1 200 OK
* Server nginx/1.6.2 is not blacklisted
< Server: nginx/1.6.2
< Date: Fri, 13 Feb 2015 20:19:56 GMT
< Content-Type: application/json; charset=utf-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-Powered-By: PHP/5.6.5
< Access-Control-Allow-Origin: *
< Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE
< Access-Control-Allow-Headers: X-AUTH-TOKEN
<
* Connection #0 to host localhost left intact
{"customer":{"id":"1","email":"mikel@franeckilang.name","company":"XXAcme","name_first":"Lyla","name_last":"Heidenreich","name_full":"Heidenreich, Lyla","phone":"785-613-0946","address":"123 Main Street","city":"Woodbridge","state":"CT","postal":"01233"}}%                                                                                                ➜  bin
```

#### Update (PUT /api/v1/customers/:id)

```
➜  bin  ./update
* Adding handle: conn: 0x7fac2280d000
* Adding handle: send: 0
* Adding handle: recv: 0
* Curl_addHandleToPipeline: length: 1
* - Conn 0 (0x7fac2280d000) send_pipe: 1, recv_pipe: 0
* About to connect() to localhost port 5002 (#0)
*   Trying ::1...
*   Trying 127.0.0.1...
* Connected to localhost (127.0.0.1) port 5002 (#0)
> PUT /api/v1/customers/1 HTTP/1.1
> User-Agent: curl/7.30.0
> Host: localhost:5002
> Accept: */*
> X-AUTH-TOKEN: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI5NzUzIiwiZXhwIjpudWxsfQ.sDg1qCCcKIo_290O6jIk7fnYDU6b6by0LweFYlW6iqE
> Content-Type: application/json
> Content-Length: 222
>
* upload completely sent off: 222 out of 222 bytes
< HTTP/1.1 200 OK
* Server nginx/1.6.2 is not blacklisted
< Server: nginx/1.6.2
< Date: Fri, 13 Feb 2015 20:22:07 GMT
< Content-Type: application/json; charset=utf-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-Powered-By: PHP/5.6.5
< Access-Control-Allow-Origin: *
< Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE
< Access-Control-Allow-Headers: X-AUTH-TOKEN
<
* Connection #0 to host localhost left intact
{"customer":{"id":"1","email":"mikel@franeckilang.name","company":"XXAcme","name_first":"Lyla","name_last":"Heidenreich","name_full":"Heidenreich, Lyla","phone":"785-613-0946","address":"123 Main Street","city":"Woodbridge","state":"CT","postal":"01233"}}%                                                                                                ➜  bin
```

#### Delete (DELETE /api/v1/customers/:id)

```
➜  bin  ./delete
* Adding handle: conn: 0x7fc67b803a00
* Adding handle: send: 0
* Adding handle: recv: 0
* Curl_addHandleToPipeline: length: 1
* - Conn 0 (0x7fc67b803a00) send_pipe: 1, recv_pipe: 0
* About to connect() to localhost port 5002 (#0)
*   Trying ::1...
*   Trying 127.0.0.1...
* Connected to localhost (127.0.0.1) port 5002 (#0)
> DELETE /api/v1/customers/34 HTTP/1.1
> User-Agent: curl/7.30.0
> Host: localhost:5002
> Accept: */*
> X-AUTH-TOKEN: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI5NzUzIiwiZXhwIjpudWxsfQ.sDg1qCCcKIo_290O6jIk7fnYDU6b6by0LweFYlW6iqE
>
< HTTP/1.1 200 OK
* Server nginx/1.6.2 is not blacklisted
< Server: nginx/1.6.2
< Date: Fri, 13 Feb 2015 20:22:34 GMT
< Content-Type: application/json; charset=utf-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-Powered-By: PHP/5.6.5
< Access-Control-Allow-Origin: *
< Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE
< Access-Control-Allow-Headers: X-AUTH-TOKEN
<
* Connection #0 to host localhost left intact
{"data":"deleted"}%                                                                                                                                                             ➜  bin
```