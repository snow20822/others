檔案上傳

之前因為把機器上原本的 apache 的 web server 換成 nginx，遇到了這個錯誤  client intended to send too large body

查了一下資料發現造成的原因為 web server 接收 request body 的大小設定

apache:
LimitRequestBody 預設為 0(unlimit)
nginx:
client_max_body_size 預設為 1m

vim /etc/nginx/nginx.conf

http {
    ...
 
    # set request body size unlimit
    client_max_body_size 0;
    ...
}



我的網頁在上傳檔案時出現413 Request Entity Too Large錯誤
伺服器環境

ubuntu 18.04 bionic

nginx

php7.1-fpm

要上傳檔案不是很大，不到2M，沒超過php 的上傳限制，查了一下，是Nginx的設定問題

編輯 /etc/nginx/sites-available/default 檔案

在 server 或 localtion 區段內，新增

client_max_body_size 20M;

存檔離開

再重啟  nginx

sudo service nginx restart

如果訊息仍出現，還要設定 php.ini 　位置在

/etc/php/7.1/fpm/php.ini，請編輯

找到 post_max_size = 8M（你的伺服器可能是其他數值）

改成 post_max_size = 32M

再找到 upload_max_filesize = 2M（你的伺服器可能是其他數值）

改成 upload_max_filesize = 32M

存檔離開

再重啟  php-fpm

sudo service php7.1-fpm restart