clone the git repo to the root of the web server:

```
cd /var/www/html
sudo git clone https://github.com/tomgsmith99/sfws.git
```

then create a symbolic link in the root of the web server:

```
sudo ln -s /var/www/html/sfws/index.php /var/www/html/index.php
```