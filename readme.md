.htaccess

# Enable rewrite engine and route requests to framework
RewriteEngine On

# Some servers require you to specify the `RewriteBase` directive
# In such cases, it should be the path (relative to the document root)
# containing this .htaccess file
#
# RewriteBase /

RewriteRule ^(tmp)\/|\.ini$ - [R=404]

RewriteCond %{REQUEST_FILENAME} !-l
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule .* index.php [L,QSA]
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization},L]
