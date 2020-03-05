
# macEvent
MQTT client to react to macEvent Topics

Install in /opt

Used the mosquitto_sub client to connect to a local server.

Software needed
 
PHP 7

curl

mosquitto

In cron add these jobs

*/5 * * * * /opt/macEvent/bin/macEventMain.php 2>&1 >>/opt/macEvent/log/cron.log ;# This to make sure the service is running

59 23 * * * /opt/macEvent/bin/macEventKill.php 2>&1 >>/opt/macEvent/log/cron.log ;# This will kill the job so the log is roated.
