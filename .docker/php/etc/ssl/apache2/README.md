# Generate cert (.crt = .pem)

```shell
openssl req -x509 -new -out server.crt -keyout server.key -days 365 -newkey rsa:4096 -sha256 -nodes
```
